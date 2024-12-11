<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $loginField = $request->input('login');

        $user = filter_var($loginField, FILTER_VALIDATE_EMAIL)
            ? User::where('email', $loginField)->first()
            : User::where('name', $loginField)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // 住所が未登録の場合は/mypage/profileにリダイレクト
            if (is_null($user->address)) {
                return redirect('/mypage/profile');
            }

            // 住所が登録されている場合はトップページにリダイレクト
            return redirect('/');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません。',
        ]);
    }
}
