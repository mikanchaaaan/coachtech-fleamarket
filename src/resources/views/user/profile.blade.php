@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile__top">
    <div class="profile-top-information">
        <img src="" alt="プロフィール画像" class="profile-img">
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
        <img src="{{$exhibition->image}}" alt="商品画像" class="profile__exhibition-img">
        <p class="profile__exhibition-name">{{$exhibition->name}}</p>
    </div>
    @endforeach
</div>
@endsection