@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login__content">
    <div class="login-form__heading">
        <h2>ログイン</h2>
    </div>
    <div class="login__content--inner">
        <form action="{{ route('login') }}" class="form" method="post">
            @csrf
            <!-- ログイン情報入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">ユーザー名/メールアドレス</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="login" value="{{ old('login') }}" />
                    </div>
                    <div class="form__error">
                        @error('login')
                        {{ $message }}
                        @enderror
                        @if (session('message'))
                            {{ session('message') }}
                        @endif
                    </div>
                </div>
            </div>
            <!-- パスワード入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">パスワード</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="password" name="password" />
                    </div>
                    <div class="form__error">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">ログインする</button>
            </div>
            <div class="go__register">
                <a href="/register" class="go__register--button">会員登録はこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection