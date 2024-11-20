@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profileedit.css') }}">
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
            <!-- 画像選択 -->
            <div class="form__group-image">
                <div class="form__group-image--display">
                </div>
                <div class="form__group-image--content">
                    <div class="form__input-image--text">
                        <input type="file" name="image" id="image" value="{{ old('image') }}" />
                        <div class="image__preview"></div>
                    </div>
                    <div class="form__error">
                        @error('image')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 名前入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">ユーザー名</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="name" value="{{ old('name') }}" />
                    </div>
                    <div class="form__error">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 郵便番号入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">郵便番号</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="postcode" value="{{ old('postcode') }}" />
                    </div>
                    <div class="form__error">
                        @error('postcode')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 住所入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">住所</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="test" name="address" />
                    </div>
                    <div class="form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 建物名入力 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">建物名</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="building" />
                    </div>
                    <div class="form__error">
                        @error('building')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <!-- 登録ボタン -->
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
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