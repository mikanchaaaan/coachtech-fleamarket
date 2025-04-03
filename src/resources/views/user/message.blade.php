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

@section('js')
    <script>
    // Laravelの認証情報をJavaScriptに渡す
    const authUserId = @json(auth()->id());  // 現在のユーザーIDをJavaScriptに渡す
    </script>
    <script src="{{ asset('js/message.js') }}"></script>
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
                @if($chat_partner->image)
                    <img src="{{ asset('storage/' . $chat_partner->image) }}" alt="プロフィール画像" class="profile-img">
                @else
                    <div class="image__none"></div>
                @endif
                <h2>{{ $chat_partner->name }}さんとの取引画面</h2>
            </div>
            <div class="product-info">
                <div class="image-box">
                    @if (filter_var($exhibition->image, FILTER_VALIDATE_URL))
                        <img src="{{ asset($exhibition->image) }}"  alt="商品画像" class="img-content"/>
                    @elseif($exhibition->image)
                        <img src="{{ asset('storage/' . $exhibition->image) }}" alt="{{ $exhibition->name }}" class="img-content"/>
                    @endif</div>
                <div class="details">
                    <h3>{{ $exhibition->name }}</h3>
                    <p class="exhibition-price__int">&#165;{{ number_format($exhibition->price) }}<span class="exhibition-price__tax">（税込）</span></p>
                </div>
            </div>
            <div class="messages" data-receiver="{{ $chat_partner->id }}" data-item-id="{{ $exhibition->id }}">
                @foreach($messages as $message)
                    @if($message->sender_id === $chat_partner->id)
                        <div class="received">
                            <div class="message">
                                <div class="receiver-icon">
                                    @if($chat_partner->image)
                                        <img src="{{ asset('storage/' . $chat_partner->image) }}" alt="プロフィール画像" class="icon-img">
                                    @else
                                        <div class="profile-image__none"></div>
                                    @endif
                                </div>
                                <div class="receiver-name">{{ $chat_partner->name }}</div>
                            </div>
                        </div>
                        <div class="message-content">{{ $message->content }}</div>
                        @if ($message->image)
                            <div class="message-image">
                                <img src="{{ Storage::url($message->image) }}" alt="送信画像" class="message-img">
                            </div>
                        @endif
                    @else
                        <div class="sent">
                            <div class="message">
                                <div class="sender-name">{{ $user->name }}</div>
                                <div class="sender-icon">
                                    @if(auth()->user()->image)
                                        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="プロフィール画像" class="icon-img">
                                    @else
                                        <div class="profile-image__none"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="message-content">{{ $message->content }}</div>
                            @if ($message->image)
                                <div class="message-image">
                                    <img src="{{ Storage::url($message->image) }}" alt="送信画像" class="message-img">
                                </div>
                            @endif
                            <div class="options">
                                <button>編集</button>
                                <button>削除</button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <footer class="chat-input">
                <input type="text" name="content" placeholder="取引メッセージを記入してください">

                <label class="add-img" for="image" style="cursor: pointer; display: inline-block; margin-right: 10px;">画像を追加</label>
                <input type="file" name="image" id="image" style="display: none;" />

                <!-- 画像プレビューを表示するエリア -->
                <div id="image-preview-container"></div>

                <button type="submit" class="send">
                    <img src="{{ asset('img/send.jpg') }}" alt="送信">
                </button>
            </footer>
        </div>
    </div>
@endsection