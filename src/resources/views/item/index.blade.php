@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
<div class="exhibition">
    <div class="exhibition-page__tab">
        <a href="/?tab=all&keyword={{ request('keyword') }}" class="exhibition-page__tab--all {{ $tab == 'all' ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist&keyword={{ request('keyword') }}" class="exhibition-page__tab--mylist {{ $tab == 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <div class="exhibition-contents">
        @foreach ($exhibitions as $exhibition)
            <div class="exhibition-content">
                <a href="/item/{{$exhibition->id}}" class="exhibition-link">
                    @if (filter_var($exhibition->image, FILTER_VALIDATE_URL))
                        <img src="{{ asset($exhibition->image) }}"  alt="商品画像" class="img-content"/>
                    @elseif($exhibition->image)
                        <img src="{{ asset('storage/' . $exhibition->image) }}" alt="{{ $exhibition->name }}" class="img-content"/>
                    @endif
                </a>
                <div class="detail-content">
                    <p>{{$exhibition->name}}</p>
                    {{-- 購入済みの商品は"sold"と表示する --}}
                    @if ($exhibition->purchases->isNotEmpty())
                    <label class="sold">Sold</label>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection