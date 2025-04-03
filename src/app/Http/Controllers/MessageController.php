<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    // 取引チャット画面の表示
    public function showMessage($item_id){
        $exhibition = Exhibition::findOrFail($item_id);
        $user = auth()->user();

        // transactions テーブルから exhibition_id に紐づく取引を取得
        $transaction = Transaction::where('exhibition_id', $item_id)->firstOrFail();

        // 自分が出品者なら receiver_id、購入者なら seller_id を渡す
        $chat_partner_id = ($user->id === $transaction->seller_id)
            ? $transaction->receiver_id
            : $transaction->seller_id;

        // 取引相手の情報を取得
        $chat_partner = User::find($chat_partner_id);

        // やり取りしているメッセージの取得
        $messages = Message::where(function ($query) use ($chat_partner_id) {
            $query->where('sender_id', auth()->id())->where('receiver_id', $chat_partner_id);
        })->orWhere(function ($query) use ($chat_partner_id) {
            $query->where('sender_id', $chat_partner_id)->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        return view('user.message', compact('exhibition', 'user', 'transaction', 'chat_partner', 'messages'));
    }

    // メッセージ送信
    public function sendMessage(Request $request)
    {
        Log::info($request);
        $imagePath = null;

        // 画像がアップロードされている場合は保存
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'exhibition_id' => $request->item_id, // exhibition_id も追加
            'content' => $request->message,
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
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'sender_name' => auth()->user()->name,
                'receiver_name' => $receiver->name,
                'sender_image' => auth()->user()->image ? asset('storage/' . auth()->user()->image) : null,
                'receiver_image' => $receiver->image ? asset('storage/' . $receiver->image) : null,
            ]
        ]);
    }
}
