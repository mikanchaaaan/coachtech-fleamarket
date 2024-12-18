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
            <div class="form__error">
                @error ('image')
                    <p>{{$message}}</p>
                @enderror
            </div>
        </div>
        <div class="sell-content-box__detail">
            <h2 class="detail__title">商品の詳細</h2>
            <div class="detail__category">
                <h3 class="detail__category--title">カテゴリー</h3>
                @foreach($categories as $category)
                <div class="detail__category--content">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}" class="category-checkbox"
                    @if(in_array($category->id, old('categories', []))) checked @endif>
                    <label for="category-{{ $category->id }}" class="category-label">{{ $category->content }}</label>
                </div>
                @endforeach
            <div class="form__error">
                @error ('categories[]')
                    <p>{{$message}}</p>
                @enderror
            </div>
            </div>
            <div class="detail__condition">
                <h3 class="detail__condition--title">商品の状態</h3>
                <select class="detail__condition--content" name="condition">
                    <option value="">選択してください</option>
                    <option value="1" @if(old('condition') == 1) selected @endif>良好</option>
                    <option value="2" @if(old('condition') == 2) selected @endif>目立った傷や汚れなし</option>
                    <option value="3" @if(old('condition') == 3) selected @endif>やや傷や汚れあり</option>
                    <option value="4" @if(old('condition') == 4) selected @endif>状態が悪い</option>
                </select>
                <div class="form__error">
                    @error ('condition')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="sell-content-box__information">
            <h2 class="information__title">商品名と説明</h2>
            <div class="information__name">
                <h3 class="information__name--title">商品名</h3>
                <input type="text" name="name" value="{{ old('name') }}" class="information__name--content">
                <h3 class="information__name--title">ブランド名</h3>
                <input type="text" name="brand_name" value="{{ old('brand_name') }}" class="information__name--content">
                <div class="form__error">
                    @error ('name')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="information__description">
                <h3 class="information__description--title">商品の説明</h3>
                <textarea name="description" id="" cols="30" rows="10" class="information__description--content">{{ old('description') }}</textarea>
                <div class="form__error">
                    @error ('description')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="information__price">
                <h3 class="information__price--title">販売価格</h3>
                <input type="text" name="price" value="{{ old('price') }}" placeholder=&#165; class="information__price--content">
                <div class="form__error">
                    @error ('price')
                        <p>{{$message}}</p>
                    @enderror
                </div>
            </div>
        </div>
        <button class="register-sell">出品する</button>
    </form>
</div>
@endsection

@section('js')
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewDiv = document.querySelector('.image__preview');
        const button = document.querySelector('.image__select--button');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.innerHTML = `<img src="${e.target.result}" alt="選択された画像">`;
                previewDiv.style.display = 'block';
                button.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.innerHTML = '';
            previewDiv.style.display = 'none';
            button.style.display = 'block';
        }
    });
</script>
@endsection