<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
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

        if ($tab == 'buy') {
            $exhibitions = auth()->user()->purchaseItems;
        } elseif ($tab == 'sell') {
            $exhibitions = auth()->user()->sellItems;
        } else {
            $exhibitions = auth()->user()->transactionItems;
        }

        return view('user.profile', compact('user','exhibitions','tab'));
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