<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use App\Models\Message;
use App\Models\Exhibition;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // プロフィール画面の表示
    public function showProfile(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'sell');

        $averageRating = $user->reviewsAsReviewee()->avg('rating');
        $averageRating = round($averageRating);

        if ($tab == 'buy') {
            $exhibitions = auth()->user()->purchaseItems;
        } elseif ($tab == 'sell') {
            $exhibitions = auth()->user()->sellItems;
        } else {
            // transactions テーブルから進行中の取引（is_active = 1）を検索
            $exhibitionIds = Transaction::where('is_active', 1)
                ->where(function ($q) use ($user) {
                    $q->where('receiver_id', $user->id) // 自分が購入者
                        ->orWhere('seller_id', $user->id); // 自分が出品者
                })
                ->pluck('exhibition_id'); // 該当する exhibition_id を取得

            // Exhibition モデルから該当する exhibition_id のデータを取得
            $exhibitions = Exhibition::whereIn('id', $exhibitionIds)
                ->with(['transaction', 'messages'])
                ->withCount(['messages as unread_messages_count' => function ($query) use ($user) {
                    $query->where('is_read', false)
                        ->where('receiver_id', $user->id);
                }])
                ->orderByDesc(
                    DB::table('messages')
                        ->select('created_at')
                        ->whereColumn('messages.exhibition_id', 'exhibitions.id')
                        ->latest()
                        ->limit(1)
                )
                ->get();

            // 取引完了（is_active = 0）かつ評価していない商品を追加で抽出
            $completedExhibitionIds = Exhibition::whereIn('id', Transaction::where('is_active', 0)
                ->where(function ($q) use ($user) {
                    $q->where('receiver_id', $user->id) // 自分が購入者
                        ->orWhere('seller_id', $user->id); // 自分が出品者
                })
                ->pluck('exhibition_id'))
                ->whereDoesntHave('reviews', function ($query) use ($user) {
                    $query->where('reviewer_id', $user->id); // 自分が評価していない商品
                })
                ->pluck('id');

            // 完了した取引で未評価の商品も追加
            $completedExhibitions = Exhibition::whereIn('id', $completedExhibitionIds)
                ->with(['transaction', 'messages'])
                ->withCount(['messages as unread_messages_count' => function ($query) use ($user) {
                    $query->where('is_read', false)
                        ->where('receiver_id', $user->id);
                }])
                ->orderByDesc(
                    DB::table('messages')
                        ->select('created_at')
                        ->whereColumn('messages.exhibition_id', 'exhibitions.id')
                        ->latest()
                        ->limit(1)
                )
                ->get();

            // 進行中の取引と未評価の商品を統合
            $exhibitions = $exhibitions->merge($completedExhibitions);
        }

        return view('user.profile', compact('user', 'exhibitions', 'tab', 'averageRating'));
    }


    // 未読メッセージの確認
    public function getUnreadMessageCount()
    {
        $user = auth()->user();

        $totalUnreadCount = Message::where('receiver_id', $user->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', 0)
            ->count();

        $exhibitions = auth()->user()->transactionItems->loadCount([
            'messages as unread_messages_count' => function ($query) use ($user) {
                $query->where('receiver_id', $user->id)
                    ->where('sender_id', '!=', $user->id)
                    ->where('is_read', 0);
            }
        ]);

        return response()->json([
            'totalUnreadCount' => $totalUnreadCount,
            'exhibitions' => $exhibitions,
        ]);
    }

    // プロフィール編集画面の表示
    public function showEditProfile()
    {
        $user = auth()->user();
        return view('user.profileedit', compact('user'));
    }

    // プロフィール編集画面の更新
    public function editProfile(AddressRequest $addressrequest, ProfileRequest $profilerequest)
    {
        $user = auth()->user();
        $isFirstTime = $user->created_at == $user->updated_at;

        /** @var User $user */
        $user->update(['name' => $profilerequest->name]);

        $address = $addressrequest->only(['postcode', 'address', 'building']);

        if ($user->address) {
            $user->address->update($address);
        } else {
            $user->address()->create(array_merge($address, ['user_id' => $user->id]));
        }

        if ($profilerequest->hasFile('image')) {
            $image = $profilerequest->file('image');
            $path = $image->store('image', 'public');
            $user->update(['image' => $path]);
        }

        return redirect('/mypage');
    }
}