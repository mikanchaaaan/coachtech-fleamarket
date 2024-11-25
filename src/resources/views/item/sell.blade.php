@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('page-move')
    <div class="header__search--box">
        <form action="{{ route('item.index') }}" method="get">
            <input type="text" name="keyword" class="header__search--input" placeholder="なにをお探しですか？" value="{{ request('keyword')}}">
            <input type="hidden" name="tab" value="{{ session('tab', 'all') }}">
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
<div class="sell-content-box">
    <div class="sell-content-box__title">
        <h1>商品の出品</h1>
    </div>
    <form action="/sell/create" class="sell-form" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-content-box__image">
            <h3 class="image__title">商品画像</h3>
            <div class="image__select">
                <label for="image" class="image__select--button">画像を選択する</label>
                <input type="file" name="image" id="image" style="display:none" accept="image/*">
                <div class="image__preview"></div>
            </div>
        </div>
        <div class="sell-content-box__detail">
            <h2 class="detail__title">商品の詳細</h2>
            <div class="detail__category">
                <h3 class="detail__category--title">カテゴリー</h3>
                @foreach($categories as $category)
                <div class="detail__category--content">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}">
                    <label for="category-{{ $category->id }}">{{ $category->content }}</label>
                </div>
                @endforeach
            </div>
            <div class="detail__condition">
                <h3 class="detail__condition--title">商品の状態</h3>
                <select class="detail__condition--content" name="condition">
                    <option value="">選択してください</option>
                    <option value="1">良好</option>
                    <option value="2">目立った傷や汚れなし</option>
                    <option value="3">やや傷や汚れあり</option>
                    <option value="4">状態が悪い</option>
                </select>
            </div>
        </div>
        <div class="sell-content-box__information">
            <h2 class="information__title">商品名と説明</h2>
            <div class="information__name">
                <h3 class="information__name--title">商品名</h3>
                <input type="text" name="name" value="">
            </div>
            <div class="information__description">
                <h3 class="information__discription--title">商品の説明</h3>
                <textarea name="description" id="" cols="30" rows="10"></textarea>
            </div>
            <div class="information__price">
                <h3 class="information__price--title">販売価格</h3>
                <input type="text" name="price" value="￥">
            </div>
        </div>
        <button class="register-sell">出品する</button>
    </form>
</div>
@endsection

@section('js')
<script>
    document.getElementById('image').addEventListener('change', function (event) {
        const file = event.target.files[0];  // 選択されたファイル
        const previewDiv = document.querySelector('.image__preview'); // プレビューを表示する場所

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.innerHTML = `<img src="${e.target.result}" alt="選択された画像" style="max-width: 100%; height: auto;">`;
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.innerHTML = '画像が選択されていません';
        }
    });
</script>
@endsection