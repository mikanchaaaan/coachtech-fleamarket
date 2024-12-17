<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Address;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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
    public function checkout(PurchaseRequest $request, $item_id)
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

        // Stripe秘密鍵を設定
        Stripe::setApiKey(config('stripe.secret_key'));

        // Stripe Checkoutセッションを作成
        $session = Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $exhibition->name,
                    ],
                    'unit_amount' => $exhibition->price * 1, // 日本円の場合、単位は1円
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['item_id' => $item_id]),
            'cancel_url' => route('checkout.cancel', ['item_id' => $item_id]),
            'locale' => 'ja',
        ]);

        // Stripe Checkoutページにリダイレクト
        return redirect($session->url);
    }

    public function success(Request $request)
    {
        // ログイン中のユーザー
        $user = auth()->user();

        // 成功時に商品IDを取得（Stripeから直接受け取るか、セッションで管理）
        $item_id = $request->query('item_id');

        // Exhibitionテーブルから商品情報を取得
        $exhibition = Exhibition::findOrFail($item_id);

        // 商品が既に購入済みかを再度確認
        $existingPurchase = Purchase::where('exhibition_id', $exhibition->id)->first();

        if ($existingPurchase) {
            return redirect("/item/{$item_id}")->with('error', 'この商品はすでに購入されています。');
        }

        // 住所情報の取得
        $address = $user->address;

        // Purchaseテーブルに登録
        Purchase::create([
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
        ]);

        return redirect('/mypage');
    }

    public function cancel()
    {
        return redirect('/')->with('error', '購入がキャンセルされました。');
    }
}
