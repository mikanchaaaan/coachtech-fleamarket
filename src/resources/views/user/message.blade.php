@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/message.css') }}">
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
    <div class="container">
        <aside class="sidebar">
            <h2>その他の取引</h2>
            <button class="item">商品名</button>
            <button class="item">商品名</button>
            <button class="item">商品名</button>
        </aside>
        <div class="chat-area">
            <div class="chat-header">
                @if(auth()->user()->image)
                    <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="プロフィール画像" class="profile-img">
                @else
                    <div class="image__none"></div>
                @endif
                <h2>{{ $user->name }}さんとの取引画面</h2>
            </div>
            <div class="product-info">
                <div class="image-box">商品画像</div>
                <div class="details">
                    <h3>商品名</h3>
                    <p>商品価格</p>
                </div>
            </div>
            <div class="messages">
                <div class="received">
                    <div class="message">
                        <div class="receiver-icon"></div>
                        <div class="receiver-name">ユーザー名</div>
                    </div>
                </div>
                <div class="message-content">相手から送られたメッセージ</div>
                <div class="sent">
                    <div class="message">
                        <div class="sender-name">ユーザー名</div>
                        <div class="sender-icon"></div>
                    </div>
                    <div class="message-content">自分が送ったメッセージ</div>
                    <div class="options">
                        <button>編集</button>
                        <button>削除</button>
                    </div>
                </div>
            </div>
            <footer class="chat-input">
                <input type="text" placeholder="取引メッセージを記入してください">
                <button class="add-img">画像を追加</button>
                <button class="send">
                    <img src="{{ asset('img/send.jpg') }}" alt="ロゴ">
                </button>
            </footer>
@endsection