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
        // ログイン中のユーザ情報を取得
        $user = auth()->user();

        // ユーザー名を更新
        /** @var User $user */
        $user->update(['name' => $request->name]);

        // 住所のデータを取得
        $address = $request->only(['postcode', 'address', 'building']);

        // 住所が存在する場合は更新、存在しない場合は新規作成
        if ($user->address) {
            // 住所が存在する場合は更新
            $user->address->update($address);
        } else {
            // 住所が存在しない場合は$user_idと紐づけて住所を新規登録
            $user->address()->create(array_merge($address, ['user_id' => $user->id]));
        }

        return redirect('/');
    }
}