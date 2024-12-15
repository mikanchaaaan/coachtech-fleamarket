<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Address;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Log;

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
        $user = auth()->user();
        return view('purchase.address', compact('item_id', 'user'));
    }

    // 配送先住所の変更
    public function editAddress(AddressRequest $request, $item_id)
    {
        // ログイン中のユーザの確認
        $user = auth()->user();

        // 情報の取得
        $address = $request->only(['postcode', 'address', 'building']);

        // Addressテーブルの更新
        $user->address->update($address);

        return redirect("/purchase/{$item_id}");
    }

    // 購入した商品をPurchaseテーブルに登録
    public function createPurchase(PurchaseRequest $request, $item_id)
    {
        // ログイン中のユーザの確認
        $user = auth()->user();

        // 住所情報の取得
        $address = $user->address;
        $address_id = $address->id;

        // Exhibitionテーブルからアイテムを取得
        $exhibition = Exhibition::findOrFail($item_id);

        // Purchaseテーブルに登録するデータを作成
        $purchaseData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
        ];

        // 商品が既に購入済みかを確認
        $existingPurchase = Purchase::where('exhibition_id', $exhibition->id)->first();

        if ($existingPurchase) {
            // すでに購入された商品
            return redirect("/item/{$item_id}")->with('error', 'この商品はすでに購入されています。');
        }

        // Purchaseテーブルに追加
        Purchase::create($purchaseData);

        // マイページにリダイレクト
        return redirect("/mypage");
    }
}
