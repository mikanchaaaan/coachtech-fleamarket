@extends('layouts.app')

@section('content')
    <p>{{ $receiver->name }}様との取引が完了しました。</p>
    <p>商品名: {{ $exhibition->name }}</p>
    <p>サイトにログインして取引相手の評価を入力してください。</p>
@endsection
