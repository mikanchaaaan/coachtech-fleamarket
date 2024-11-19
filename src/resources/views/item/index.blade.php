@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="exhibition">
    <div class="exhibition-page__tab">
        <a href="/" class="exhibition-page__tab--all">おすすめ</a>
        <a href="/?tab=mylist" class="exhibition-page__tab--mylist">マイリスト</a>
    </div>
    <div class="exhibition-contents">
        @foreach ($exhibitions as $exhibition)
            <div class="exhibition-content">
                <a href="/item/{{$exhibition->id}}" class="exhibition-link">
                <img src="{{ asset($exhibition->image) }}"  alt="商品画像" class="img-content"/>
                </a>
                <div class="detail-content">
                    <p>{{$exhibition->name}}</p>
                    {{-- 購入済みの商品は"sold"と表示する --}}
                    @if ($exhibition->purchases->isNotEmpty())
                        <span class="sold">Sold</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection