@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profileedit.css') }}">
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

<!-- ユーザ情報登録画面 -->
@section('content')
    <div class="profileedit__content">
        <div class="profileedit-form__heading">
            <h2>プロフィール設定</h2>
        </div>
        <div class="profileedit__content--inner">
            <form action="/mypage/profile/edit" class="form__image" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form__group-image">
                    <div class="form__group-image--display">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="プロフィール画像" class="profile__image">
                        @else
                            <div class="image__none"></div>
                        @endif
                    </div>

                    <div class="form__group-image--content">
                        <div class="form__input-image--text">
                            <div class="image__preview"></div>
                            <label for="image" class="image-change">画像を選択する</label>
                            <input type="file" name="image" id="image" value="{{ old('image') }}" />
                        </div>
                        <div class="form__error">
                            @error('image')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">ユーザー名</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" />
                        </div>
                        <div class="form__error">
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">郵便番号</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="postcode" value="{{ old('postcode', optional($user->address)->postcode) }}" />
                        </div>
                        <div class="form__error">
                            @error('postcode')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">住所</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="address" value="{{ old('address', optional($user->address)->address) }}"/>
                        </div>
                        <div class="form__error">
                            @error('address')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">建物名</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="building" value="{{ old('building', optional($user->address)->building) }}"/>
                        </div>
                        <div class="form__error">
                            @error('building')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form__button">
                    <button class="form__button-submit" type="submit">更新する</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/profileedit.js') }}"></script>
@endsection