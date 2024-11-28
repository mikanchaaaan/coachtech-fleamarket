<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required'],
            'image' => ['required', 'mimetypes:image/jpeg,image/png'],
            'description' => ['required', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'condition' => ['required'],
            'categories' => ['required', 'array', 'min:1'],
        ];
    }

    public function messages()
    {
        return[
            'name.required' => '商品名を入力してください',
            'image.required' => '商品画像を入力してください',
            'image.mimetypes' => '商品画像はjpegまたはpng形式でアップロードしてください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は整数で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
            'condition.required' => '商品状態を選択してください',
            'categories.required' => '商品カテゴリーを選択してください',
            'categories.array' => 'カテゴリーは配列である必要があります',
            'categories.min' => '少なくとも1つのカテゴリーを選択してください',
        ];
    }
}
