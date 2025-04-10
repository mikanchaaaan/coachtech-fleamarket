<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Message;
use App\Models\Review;
use App\Http\Requests\MessageRequest;
use App\Mail\TransactionCompletedMail;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    // 取引チャット画面の表示
    public function showMessage($item_id){
        $exhibition = Exhibition::findOrFail($item_id);
        $user = auth()->user();

        $ongoingExhibitions = Transaction::where(function ($query) use ($user) {
            $query->where('seller_id', $user->id)  // 自分が出品者
                ->where('is_active', 1);         // 出品者の取引は進行中（is_active = 1）
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('receiver_id', $user->id) // 自分が購入者
                    ->where('is_active', 1);          // 購入者の取引も進行中（is_active = 1）
            })
            ->with('exhibition')
            ->get();

        $transaction = Transaction::where('exhibition_id', $item_id)->firstOrFail();
        $reviewStatus = $transaction ? $transaction->reviews()->where('reviewer_id', auth()->id())->exists() : false;

        $chat_partner_id = ($user->id === $transaction->seller_id)
            ? $transaction->receiver_id
            : $transaction->seller_id;
        $chat_partner = User::find($chat_partner_id);

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
        $message = Message::findOrFail($id);

        if ($message) {
            if ($message->sender_id != auth()->user()->id && $message->is_read == 0) {
                $message->update(['is_read' => 1]);
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => true]);
    }

    // メッセージ送信
    public function sendMessage(MessageRequest $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('message', 'public');
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'exhibition_id' => $request->item_id,
            'content' => $request->content,
            'image' => $imagePath,
            'is_read' => false
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
        $exhibition = Exhibition::findOrFail($exhibition_id);
        $transaction = $exhibition->transaction()->where('exhibition_id', $exhibition_id)->first();

        $isBuyerReviewing = $transaction->receiver_id == auth()->id();

        if ($isBuyerReviewing) {
            $reviewer_id = auth()->id();
            $reviewee_id = $transaction->seller_id;
        } else {
            $reviewer_id = auth()->id();
            $reviewee_id = $transaction->receiver_id;
        }

        $review = new Review();
        $review->exhibition_id = $exhibition->id;
        $review->reviewer_id = $reviewer_id;
        $review->reviewee_id = $reviewee_id;
        $review->transaction_id = $transaction->id;
        $review->rating = $request->input('rating');
        $review->save();

        $transaction->is_active = 0;
        $transaction->save();

        if ($isBuyerReviewing) {
            $seller = $transaction->seller;
            Mail::to($seller->email)->send(new TransactionCompletedMail($exhibition));
        }

        return redirect('/');
    }
}
