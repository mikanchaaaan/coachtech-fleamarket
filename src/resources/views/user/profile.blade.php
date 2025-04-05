@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('page-move')
    <div class="header__search--box">
        <form action="{{ route('item.index') }}" method="get">
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

@section('content')
<div class="profile__top">
    @if(auth()->user()->image)
        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="プロフィール画像" class="profile-img">
    @else
        <div class="image__none"></div>
    @endif
    <h2 class="profile-name">{{ $user->name }}</h2>
    <a href="/mypage/profile" class="goto-profileedit">プロフィールを編集</a>
</div>

<div class="profile__tab">
    <a href="/mypage?tab=sell" class="purchase-exhibition {{ $tab == 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="/mypage?tab=buy" class="sell-exhibition {{ $tab == 'buy' ? 'active' : '' }}">購入した商品</a>
    <a href="/mypage?tab=transaction" class="transaction-exhibition {{ $tab == 'transaction' ? 'active' : '' }}">
        取引中の商品
        <span class="unread-count-total">0</span>
    </a>
</div>

<div class="profile__content">
    @foreach($exhibitions as $exhibition)
    <div class="profile-exhibition" data-exhibition-id="{{ $exhibition->id }}">
        <a href="{{ $tab == 'transaction' ? '/message/' . $exhibition->id : '/item/' . $exhibition->id }}" class="exhibition-link">
        @if (filter_var($exhibition->image, FILTER_VALIDATE_URL))
            <img src="{{ $exhibition->image }}"  alt="{{ $exhibition->name }}" class="profile__exhibition-img"/>
        @elseif($exhibition->image)
            <img src="{{ asset('storage/' . $exhibition->image) }}" alt="{{ $exhibition->name }}" class="profile__exhibition-img"/>
        @endif
        </a>
        <p class="profile__exhibition-name">{{$exhibition->name}}</p>

        <!-- ここでtransactionタブの場合のみ未読メッセージ数を表示 -->
        @if($tab == 'transaction')
            <div class="unread-count">
                {{ $exhibition->unread_messages_count > 0 ? $exhibition->unread_messages_count : '' }}
            </div>
        @endif
    </div>
    @endforeach
</div>
@endsection

@section('js')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
