<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Address;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
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
        $user = auth()->user();
        $address = $request->only(['postcode', 'address', 'building']);

        $user->address->update($address);

        return redirect("/purchase/{$item_id}");
    }

    // 購入した商品をPurchaseテーブルに登録
    public function checkout(PurchaseRequest $request, $item_id)
    {
        $user = auth()->user();

        $address = $user->address;
        $address_id = $address->id;

        $exhibition = Exhibition::findOrFail($item_id);

        $purchaseData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
        ];

        $existingPurchase = Purchase::where('exhibition_id', $exhibition->id)->first();

        if ($existingPurchase) {
            return redirect("/item/{$item_id}")->with('error', 'この商品はすでに購入されています。');
        }

        Stripe::setApiKey(config('stripe.secret_key'));

        $session = Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $exhibition->name,
                    ],
                    'unit_amount' => $exhibition->price * 1,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['item_id' => $item_id]),
            'cancel_url' => route('checkout.cancel', ['item_id' => $item_id]),
            'locale' => 'ja',
        ]);

        return redirect($session->url);
    }

    // Stripe決済に成功したとき
    public function success(Request $request)
    {
        $user = auth()->user();
        $item_id = $request->query('item_id');

        $exhibition = Exhibition::findOrFail($item_id);
        $address = $user->address;

        Purchase::create([
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
        ]);

        return redirect('/mypage');
    }

    // Stripe決済をキャンセルしたとき
    public function cancel()
    {
        return redirect('/');
    }
}
