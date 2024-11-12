<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;

class UserController extends Controller
{
    // プロフィール編集画面の表示
    public function showEditProfile()
    {
        return view('user.profileedit');
    }

    // プロフィール編集画面の更新
    public function editProfile(Request $request)
    {
        $profile = $request->only(['postcode', 'address', 'building']);
    }
}
