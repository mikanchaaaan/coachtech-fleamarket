@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('page-move')
    <div class="header__search--box">
        <form action="/item/search" class="header__search--form">
            @csrf
            <input type="text" class="header__search--input" value="なにをお探しですか？">
        </form>
    </div>
    <div class="header__button--logout">
        <form action="/logout" class="logout-form" method="post">
            @csrf
            <button class="logout-button">ログアウト</button>
        </form>
    </div>
    <div class="header__button--mypage">
        <a href="/mypage" class="goto-mypage">マイページ</a>
    </div>
    <div class="header__button--sell">
        <a href="/sell" class="goto-sell">出品</a>
    </div>
@endsection

@section('content')
<div class="address-edit__content">
    <div class="address-edit-form__title">
        <h2>住所の変更</h2>
    </div>
    <div class="address-edit-form__content">
        <form action="/purchase/address/edit/{{ $item_id }}" class="form__address" method="post">
        @csrf
            <!-- 郵便番号入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">郵便番号</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="postcode" value="{{ old('postcode') }}" />
                    </div>
                    <div class="form__error">
                        @error('postcode')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 住所入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">住所</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="test" name="address" />
                    </div>
                    <div class="form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 建物名入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">建物名</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="building" />
                    </div>
                    <div class="form__error">
                        @error('building')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 登録ボタン -->
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection