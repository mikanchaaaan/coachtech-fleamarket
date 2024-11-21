@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
<div class="profile__top">
    <div class="profile-top-information">
        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="プロフィール画像" class="profile-img">
        <h2 class="profile-name">{{ $user->name }}</h2>
    </div>
    <a href="/mypage/profile" class="goto-profileedit">プロフィールを編集</a>
</div>

<div class="profile__tab">
    <a href="/mypage?tab=sell" class="purchase-exhibition" {{ $tab == 'sell' ? 'active' : '' }}>出品した商品</a>
    <a href="/mypage?tab=buy" class="sell-exhibition" {{ $tab == 'buy' ? 'active' : '' }}>購入した商品</a>
</div>

<div class="profile__content">
    @foreach($exhibitions as $exhibition)
    <div class="profile-exhibition">
        @if (filter_var($exhibition->image, FILTER_VALIDATE_URL))
            <img src="{{ asset($exhibition->image) }}"  alt={{ $exhibition->name }} class="profile__exhibition-img"/>
        @elseif($exhibition->image)
            <img src="{{ asset('storage/' . $exhibition->image) }}" alt="{{ $exhibition->name }}" class="profile__exhibition-img"/>
        @endif
        <p class="profile__exhibition-name">{{$exhibition->name}}</p>
    </div>
    @endforeach
</div>
@endsection