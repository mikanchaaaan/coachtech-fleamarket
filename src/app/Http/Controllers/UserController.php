<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;

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
            $exhibitions = auth()->user()->transactionItems()
                ->orderByDesc(function ($query) {
                    $query->select('created_at')
                        ->from('messages')
                        ->whereColumn('messages.exhibition_id', 'exhibitions.id')
                        ->latest()
                        ->limit(1);
                })
                ->get();
        }

        return view('user.profile', compact('user','exhibitions','tab', 'averageRating'));
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