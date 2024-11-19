@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
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
                <form action="/item/likes/:item_id" class="likes-form" method="post">
                <div class="likes__mark">
                    <button class="likes__mark--button">
                        <i class="fa-regular fa-star"></i>
                    </button>
                </div>
                </form>
                <div class="comment__mark">
                    <i class="fa-regular fa-comment"></i>
                </div>
            </div>
            <div class="goto-purchase">
                <a href="/purchase/{{ $exhibition->id }}" class="purchase-button">購入手続きへ</a>
            </div>
        </div>

        <div class="exhibition-discription">
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
            <h2 class="exhibition-comment">コメント（）</h2>
            <div class="exhibition-comment__user">
                <img src="" alt="プロフィール画像" class="comment-user__img">
                <p class="comment-user__name"></p>
            </div>
            <div class="exhibition-comment__content--display">
                <p>こちらにコメントが入ります。</p>
            </div>
            <div class="exhibition-comment__content--input">
                <h3 class="comment-input__title">商品へのコメント</h3>
                <textarea name="" id="" cols="30" rows="10" class="comment-input"></textarea>
                <button class="comment-input__button">コメントを送信する</button>
            </div>
        </div>
    </div>
</div>
@endsection