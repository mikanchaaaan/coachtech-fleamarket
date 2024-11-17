<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Address;

class PurchaseController extends Controller
{
    // 商品購入画面の表示
    public function showPurchase($item_id)
    {
        $exhibition = Exhibition::findOrFail($item_id);

        $user = auth()->user();
        $postcode = $user->postcode;
        $address = $user->address;
        $building = $user->building;

        return view('purchase.purchase', compact('exhibition','postcode', 'address', 'building'));
    }

    // 住所変更画面の表示
    public function showAddress($item_id)
    {
        return view('purchase.address', compact('item_id'));
    }

    // 配送先住所の変更
    public function editAddress(Request $request, $item_id)
    {
        // ログイン中のユーザの確認
        $user = auth()->user();

        // 情報の取得
        $address = $request->only(['postcode', 'address', 'building']);

        // Addressテーブルの更新
        $user->address->update($address);

        return redirect("/purchase/{$item_id}")->with('message', '配送先を変更しました。');

    }
}
