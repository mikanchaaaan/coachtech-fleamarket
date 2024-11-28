<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'image' => ['nullable', 'mimetypes:image/jpeg,image/png'],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'プロフィール画像を入力してください',
            'image.mimetypes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
        ];
    }
}
