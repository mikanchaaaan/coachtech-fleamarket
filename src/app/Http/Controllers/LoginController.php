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

            return redirect('/');
        }
        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません。',
        ]);
    }
}
