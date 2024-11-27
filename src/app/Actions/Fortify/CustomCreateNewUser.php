<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\RegisterRequest;

class CustomCreateNewUser implements CreatesNewUsers
{
    /**
     * ユーザー作成処理
     *
     * @param array $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // バリデーション
        $request = app(RegisterRequest::class);
        $validated = $request->validate();

        // ユーザー作成
        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
    }
}
