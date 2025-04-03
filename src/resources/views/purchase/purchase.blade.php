@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('page-move')
    <div class="header__search--box">
        <form action="{{ route('item.index') }}" method="get" class="search-form">
            <input type="text" name="keyword" class="header__search--input" placeholder="なにをお探しですか？" value="{{ request('keyword')}}">
            <input type="hidden" name="tab" value="{{ session('tab', 'all') }}">
        </form>
    </div>
    <div class="header__button">
        @auth
            <!-- ログインしている場合 -->
            <div class="header__button--logout">
                <form action="/logout" class="logout-form" method="post">
                    @csrf
                    <button class="logout-button">ログアウト</button>
                </form>
            </div>
        @else
            <!-- ログインしていない場合 -->
            <div class="header__button--login">
                <a href="/login" class="login-button">ログイン</a>
            </div>
        @endauth
        <div class="header__button--mypage">
            <a href="/mypage" class="goto-mypage">マイページ</a>
        </div>
        <div class="header__button--sell">
            <a href="/sell" class="goto-sell">出品</a>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection

@section('content')
<form action="/checkout/{{ $exhibition->id }}" class="purchase-complete" method="post">
    @csrf
    <div class="purchase-content">
        <div class="purchase-content__left">
            <div class="purchase-content__left--information">
                <div class="exhibition-imgbox">
                    @if (filter_var($exhibition->image, FILTER_VALIDATE_URL))
                        <img src="{{ $exhibition->image }}" alt="商品画像" class="exhibition-img">
                    @else
                        <img src="{{ asset('storage/' . $exhibition->image) }}" alt="商品画像" class="exhibition-img">
                    @endif
                </div>
                <div class="exhibition-contentbox">
                    <h2 class="exhibition-title">{{ $exhibition->name }}</h2>
                    <span class="exhibition-price">&#165;{{ number_format($exhibition->price) }}</span>
                </div>
            </div>
            <div class="purchase-content__left--payment">
                <h3 class="payment-title">支払い方法</h3>
                    <div class="form__error">
                        @error('payment-select')
                            {{ $message }}
                        @enderror
                    </div>
                <select id="payment" class="payment-select" name="payment-method" onchange="updateDisplay()">
                    <option value="">選択してください</option>
                    <option value="convenience_payment">コンビニ払い</option>
                    <option value="card_payment">カード支払い</option>
                </select>
                <div class="form__error">
                    @error('payment-method')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="purchase-content__left--address">
                <div class="address-title-box">
                    <h3 class="address-title">配送先</h3>
                    <a href="/purchase/address/{{ $exhibition->id }}" class="address-change-link">変更する</a>
                </div>
                <p class="address-postcode">〒 {{ $address->postcode }}</p>
                    <div class="form__error">
                        @error('postcode')
                            {{ $message }}
                        @enderror
                    </div>
                <div class="address-building-box">
                    <p class="address-address">{{ $address->address }}</p>
                        <div class="form__error">
                            @error('address')
                                {{ $message }}
                            @enderror
                        </div>
                    <p class="address-building">{{ $address->building }}</p>
                        <div class="form__error">
                            @error('building')
                                {{ $message }}
                            @enderror
                        </div>
                </div>
            </div>
        </div>
        <div class="purchase-content__right">
            <div class="purchase-content__right--confirm">
                <div class="purchase-price">
                    <p class="purchase-price-title">商品代金</p>
                    <span class="purchase-price-content">&#165;{{ number_format($exhibition->price) }}</span>
                </div>
                <div class="purchase-payment">
                    <p class="purchase-payment-title">支払い方法</p>
                    <span id="display" class="purchase-payment-content">選択された支払い方法はここに表示されます</span>
                </div>
            </div>
                <button class="purchase-content__right--button">購入する</button>
                <input type="hidden" class="purchase__exhibition-id" name="exhibition-id" value="{{ $exhibition->id }}">
        </div>
    </div>
</form>
@endsection
