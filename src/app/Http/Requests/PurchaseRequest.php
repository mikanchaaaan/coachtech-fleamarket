<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment-method' => ['required'],
            'postcode' => ['required_if:postcode,null'],
            'address' => ['required_if:address,null'],
            'building' => ['required_if:building,null'],
        ];
    }

    public function messages()
    {
        return [
            'payment-method.required' => '支払い方法を選択してください',
            'postcode.required_if' => '郵便番号を指定してください',
            'address.required_if' => '住所を指定してください',
            'building.required_if' => '建物名を指定してください',
        ];
    }
}
