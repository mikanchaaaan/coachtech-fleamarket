<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => ['required', function ($attribute, $value, $fail) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return;
                }

                if (empty($value) || !User::where('name', $value)->exists()) {
                    $fail($this->messages()['login.email']);
                }
            }],
            'password' => ['required', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'メールアドレスを入力してください',
            'login.email' => 'メールアドレス形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
    }
}