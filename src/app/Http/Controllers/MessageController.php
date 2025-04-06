<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Message;
use App\Models\Review;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    // 取引チャット画面の表示
    public function showMessage($item_id){
        $exhibition = Exhibition::findOrFail($item_id);
        $user = auth()->user();

        // ログイン中のユーザがやり取りしてる商品を確認
        $ongoingExhibitions = Transaction::where('seller_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with('exhibition')
            ->get();

        // transactions テーブルから exhibition_id に紐づく取引を取得
        $transaction = Transaction::where('exhibition_id', $item_id)->firstOrFail();

        // レビューのステータスを取得
        $reviewStatus = $transaction ? $transaction->reviews()->where('reviewer_id', auth()->id())->exists() : false;

        // 自分が出品者なら receiver_id、購入者なら seller_id を渡す
        $chat_partner_id = ($user->id === $transaction->seller_id)
            ? $transaction->receiver_id
            : $transaction->seller_id;

        // 取引相手の情報を取得
        $chat_partner = User::find($chat_partner_id);

        // やり取りしているメッセージの取得
        $messages = Message::where('exhibition_id', $item_id)
            ->where(function ($query) use ($chat_partner_id) {
                $query->where(function ($q) use ($chat_partner_id) {
                    $q->where('sender_id', auth()->id())
                        ->where('receiver_id', $chat_partner_id);
                })->orWhere(function ($q) use ($chat_partner_id) {
                    $q->where('sender_id', $chat_partner_id)
                        ->where('receiver_id', auth()->id());
                });
            })
            ->orderBy('created_at')
            ->get();

        return view('user.message', compact('ongoingExhibitions', 'exhibition', 'user', 'transaction', 'reviewStatus', 'chat_partner', 'messages'));
    }

    // 未読メッセージを既読に変更
    public function markAsRead($id){
        // メッセージIDでメッセージを取得
        $message = Message::findOrFail($id);
        Log::info('Message:', [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'content' => $message->content,
            'is_read' => $message->is_read,
        ]);

        if ($message) {
            // 送信者が自分以外で、未読メッセージの場合のみ更新
            if ($message->sender_id != auth()->user()->id && $message->is_read == 0) {
                $message->update(['is_read' => 1]);
                return response()->json(['success' => true]);
            }
        }
        // 成功のレスポンスを返す
        return response()->json(['success' => true]);
    }

    // メッセージ送信
    public function sendMessage(MessageRequest $request)
    {
        $imagePath = null;

        // 画像がアップロードされている場合は保存
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'exhibition_id' => $request->item_id, // exhibition_id も追加
            'content' => $request->content,
            'image' => $imagePath, // 保存した画像のパスを保存
            'is_read' => false // 既読/未読管理
        ]);

        // 受信者の情報を取得
        $receiver = User::find($request->receiver_id);

        // 送信したメッセージ情報を JSON で返す
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->content,
                'image' => $imagePath ? asset('storage/' . $imagePath) : null, // 画像のフルURLを返す
                'exhibition_id' => $message->exhibition_id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'sender_name' => auth()->user()->name,
                'receiver_name' => $receiver->name,
                'sender_image' => auth()->user()->image ? asset('storage/' . auth()->user()->image) : null,
                'receiver_image' => $receiver->image ? asset('storage/' . $receiver->image) : null,
            ]
        ]);
    }

    // メッセージ編集
    public function updateMessage(Request $request, $id){
        $message = Message::findOrFail($id);
        if (auth()->id() !== $message->sender_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message->content = $request->input('content');
        $message->save();

        return response()->json(['success' => true]);
    }

    // メッセージ削除
    public function destroyMessage($id){
        $message = Message::findOrFail($id);

        if (auth()->id() !== $message->sender_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message->delete();
        return response()->json(['success' => true]);
    }

    // 取引評価の登録
    public function transactionReview(Request $request, $exhibition_id)
    {
        // exhibition_idで取引対象となる商品情報を取得
        $exhibition = Exhibition::findOrFail($exhibition_id);

        // 取引情報を取得 (出品者または購入者かを判定)
        $transaction = $exhibition->transactions()->where('exhibition_id', $exhibition_id)->first();

        // 評価者と非評価者のIDを判別
        if ($transaction->receiver_id == auth()->id()) {
            // 購入者が評価を行う
            $reviewer_id = auth()->id();  // 評価者（購入者）
            $reviewee_id = $transaction->seller_id;    // 非評価者（出品者）
        } else {
            // 出品者が評価を行う
            $reviewer_id = auth()->id();    // 評価者（出品者）
            $reviewee_id = $transaction->receiver_id;  // 非評価者（購入者）
        }

        // 評価の保存
        $review = new Review();
        $review->exhibition_id = $exhibition->id;
        $review->reviewer_id = $reviewer_id;  // 評価者
        $review->reviewee_id = $reviewee_id;  // 非評価者
        $review->transaction_id = $transaction->id;
        $review->rating = $request->input('rating');  // 星の評価
        $review->save();

        // 取引のis_activeを更新 (取引完了後、アクティブでない状態に変更)
        $transaction->is_active = 0; // 取引を非アクティブ化
        $transaction->save();

        // 保存後、リダイレクト（商品一覧ページに戻す）
        return redirect('/');
    }
}
