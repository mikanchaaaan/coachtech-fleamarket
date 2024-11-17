@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-content">
    <div class="purchase-content__left">
        <div class="purchase-content__left--information">
            <div class="exhibition-imgbox">
                <img src="{{ asset($exhibition->image)}}" alt="商品画像" class="exhibition-img">
            </div>
            <div class="exhibition-contentbox">
                <h2 class="exhibition-title">商品名</h2>
                <p class="exhibition-price">{{ $exhibition->price }}</p>
            </div>
        </div>
        <div class="purchase-content__left--payment">
            <h3 class="payment-title">支払い方法</h3>
            <select id="payment" class="payment-select" onchange="updateDisplay()">
                <option value="convenience_payment">コンビニ払い</option>
                <option value="card_payment">カード支払い</option>
            </select>
        </div>
        <div class="purchase-content__left--address">
            <div class="address-title-box">
                <h3 class="address-title">配送先</h3>
                <a href="/purchase/address/{{ $exhibition->id }}" class="address-change-link">変更する</a>
            </div>
            <p class="address-postcode">{{ $address->postcode }}</p>
            <p class="address-address">{{ $address->address }}</p>
            <p class="address-building">{{ $address->building }}</p>
        </div>
    </div>
    <div class="purchase-content__right">
        <div class="purchase-content__right--confirm">
            <div class="purchase-price">
                <p class="purchase-price-title">商品代金</p>
                <p class="purchase-price-content">{{ $exhibition->price }}</p>
            </div>
            <div class="purchase-payment">
                <p class="purchase-payment-title">支払い方法</p>
                <p id="display" class="purchase-paymane-content">選択された支払い方法はここに表示されます</p>
            </div>
        </div>
        <form action="/purchase/complete/:item_id" class="purchase-complete" method="post">
            <button class="purchase-content__right--button">購入する</button>
        </form>
    </div>
</div>
@endsection

<script>
    // window.onloadを使ってDOMが読み込まれた後に処理を行う
    window.onload = function() {
        // 初期状態で支払い方法を表示
        updateDisplay();

        // 支払い方法の変更があった場合に表示を更新
        const selectElement = document.getElementById("payment");
        selectElement.addEventListener("change", updateDisplay);
    };

    function updateDisplay() {
        // Select要素を取得
        const selectElement = document.getElementById("payment");
        // 選択された値を取得
        const selectedValue = selectElement.options[selectElement.selectedIndex].text;
        // 表示用の要素を更新
        document.getElementById("display").textContent = `${selectedValue}`;
    }
</script>
