<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // プロフィール画面の表示
    public function showProfile(Request $request)
    {
        // ログイン中のユーザ情報を取得
        $user = auth()->user();

        $tab = $request->query('tab', 'sell'); // デフォルトは 'sell'

        if ($tab == 'buy') {
            $exhibitions = auth()->user()->purchaseItems;  // 購入した商品を取得
        } else {
            $exhibitions = auth()->user()->sellItems;  // 出品した商品を取得
        }

        return view('user.profile', compact('user','exhibitions','tab'));
    }

    // プロフィール編集画面の表示
    public function showEditProfile()
    {
        // ログイン中のユーザ情報を取得
        $user = auth()->user();

        return view('user.profileedit', compact('user'));
    }

    // プロフィール編集画面の更新
    public function editProfile(Request $request)
    {
        // ログイン中のユーザ情報を取得
        $user = auth()->user();

        // 初回登録かどうかをチェック（`created_at`を使う場合）
        $isFirstTime = $user->created_at == $user->updated_at;

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

        // プロフィール画像の更新
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('image', 'public');
            $user->update(['image' => $path]); // 新規登録でも適切に処理される
        }

        // 初回登録の場合はログアウトしてからログイン画面にリダイレクト
        if ($isFirstTime) {
            auth()->logout();
            return redirect('/login');
        }

        // プロフィール更新時はマイページにリダイレクト
        return redirect('/mypage');
    }
}