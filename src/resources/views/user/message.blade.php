@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/message.css') }}">
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
    <div class="container">
        <aside class="sidebar">
            <h2>その他の取引</h2>
            @foreach ($ongoingExhibitions as $ongoing)
                <div class="chatItem-choice">
                    <a href="{{ url('/message/' . $ongoing->exhibition_id) }}">
                        {{ $ongoing->exhibition->name }}
                    </a>
                </div>
            @endforeach
        </aside>

        <div id="transactionData"
            data-is-seller="{{ Auth::id() === $transaction->seller_id ? 'true' : 'false' }}"
            data-is-complete="{{ $transaction->is_active === 0 ? 'true' : 'false' }}"
            data-is-reviewed="{{ $reviewStatus ? 'true' : 'false' }}">
        </div>

        <div class="chat-area">
            <div class="chat-header">
                @if($chat_partner->image)
                    <img src="{{ asset('storage/' . $chat_partner->image) }}" alt="プロフィール画像" class="profile-img">
                @else
                    <div class="image__none"></div>
                @endif
                <h2>{{ $chat_partner->name }}さんとの取引画面</h2>
                <div class="button-container">
                    @if (Auth::id() == $transaction->receiver_id)
                        <button type="button" class="complete-button" id="openModalButton">
                            取引を完了する
                        </button>
                    @endif
                </div>
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
                    <div class="msg" data-message-id="{{ $message->id }}" data-sender-id="{{ $message->sender_id }}" data-is-read="{{ $message->is_read }}">
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
                                    <button class="edit-btn" data-message-id="{{ $message->id }}">編集</button>
                                    <button class="delete-btn" data-message-id="{{ $message->id }}">削除</button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <footer class="chat">
                <div class="form__error"></div>
                <div class="chat-input">
                    <input type="text" name="content" placeholder="取引メッセージを記入してください" value="{{ old('content') }}">
                    <label class="add-img" for="image" style="cursor: pointer; display: inline-block; margin-right: 10px;">画像を追加</label>
                    <input type="file" name="image" id="image" style="display: none;" />
                    <div id="image-preview-container"></div>
                    <button type="submit" class="send">
                        <img src="{{ asset('img/send.jpg') }}" alt="送信">
                    </button>
                </div>
            </footer>
        </div>
    </div>

    <!-- モーダル -->
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title">取引が完了しました。</p>
            </div>
            <div class="modal-body">
                <form action="/transaction/rate/{{ $exhibition->id }}" method="POST" id="ratingForm">
                    @csrf
                    <div class="rating-box">
                        <label for="rating" class="form-label">今回の取引相手はどうでしたか？</label>
                        <div class="rating-container">
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <input type="hidden" id="rating" name="rating" value="0" required />
                    </div>
                    <div class="send-box">
                        <button type="submit" class="submit-btn btn-primary">送信する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
    // Laravelの認証情報をJavaScriptに渡す
    const authUserId = @json(auth()->id());  // 現在のユーザーIDをJavaScriptに渡す
    </script>
    <script src="{{ asset('js/message.js') }}"></script>
    <script src="{{ asset('js/review.js') }}"></script>
@endsection