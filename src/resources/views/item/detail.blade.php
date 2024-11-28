@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('page-move')
    <div class="header__search--box">
        <form action="{{ route('item.index') }}" method="get">
            <input type="text" name="keyword" class="header__search--input" placeholder="なにをお探しですか？" value="{{ request('keyword')}}">
            <input type="hidden" name="tab" value="{{ session('tab', 'all') }}">
        </form>
    </div>
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
@endsection

@section('content')
<div class="exhibition-detail">
    <div class="left-block">
        <img src="{{ asset($exhibition->image)}}" alt="商品画像" class="exhibition-image">
    </div>
    <div class="right-block">
        <div class="exhibition">
            <h1 class="exhibition-title">{{ $exhibition->name }}</h1>
            <p class="exhibition-brand_name">{{ $exhibition->brand_name }}</p>
            <p class="exhibition-price">\{{ number_format($exhibition->price) }}(税込)</p>
            <div class="likes-comment__mark">
                <form action="/item/likes/{{ $exhibition->id }}" class="likes-form" method="post">
                    @csrf
                    <div class="likes__mark">
                        <button class="likes__mark--button">
                            <i class="fa-regular fa-star">
                                @if ($exhibition->likes()->where('user_id', auth()->id())->exists())
                                    いいね済み
                                @else
                                    いいね
                                @endif
                            </i>
                        </button>
                        <p class="likes__count">{{ $countLikes }}</p>
                    </div>
                </form>
                <div class="comment__mark">
                    <i class="fa-regular fa-comment"></i>
                    <p class="comments__count">{{ $countComments }}</p>
                </div>
            </div>
            <div class="goto-purchase">
                <a href="/purchase/{{ $exhibition->id }}" class="purchase-button">購入手続きへ</a>
            </div>
        </div>

        <div class="exhibition-description">
            <h2 class="exhibition-description__title">商品説明</h2>
            <p class="exhibition-description__message">{{ $exhibition->description }}</p>
        </div>

        <div class="exhibition-information">
            <h2 class="exhibition-information__title">商品の情報</h2>
            <div class="exhibition-information__category--block">
                <h3 class="exhibition-information__category--title">カテゴリー</h3>
                @foreach($exhibition->categories as $category)
                    <div>{{ $category->content }}</div>
                @endforeach
            </div>
            <div class="exhibition-information__condition--block">
                <h3 class="exhibition-information__condition--title">商品の状態</h3>
                <p class="exhibition-information__condition">{{ $condition }}</p>
            </div>
        </div>

        <div class="exhibition-comment">
            <form action="/item/comments/{{ $exhibition->id }}" class="comment-form" method="post">
            @csrf
                <h2 class="exhibition-comment">コメント（{{ $countComments }}）</h2>
                @foreach($comments as $comment)
                    <div class="exhibition-comment__user">
                        <img src="{{ asset('storage/' . $comment->user->image) }}" alt="{{ $comment->user->name }}" class="comment-user__img">
                        <p class="comment-user__name">{{ $comment->user->name }}</p>
                    </div>
                    <div class="exhibition-comment__content--display">
                        <p>{{ $comment->content }}</p>
                    </div>
                @endforeach
                <div class="exhibition-comment__content--input">
                    <h3 class="comment-input__title">商品へのコメント</h3>
                    @error('content')
                        <p>{{ $message }}</p>
                    @enderror
                    <textarea name="content" cols="30" rows="10" class="comment-input"></textarea>
                    <button class="comment-input__button">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection