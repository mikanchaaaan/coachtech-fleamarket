<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 商品一覧画面の表示
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// 商品詳細画面の表示
Route::get('/item/{item_id}', [ItemController::class, 'detail']);

Route::middleware('auth')->group(function () {
    // プロフィール画面の表示
    Route::get('mypage', [UserController::class, 'showProfile']);

    // プロフィール編集画面の表示
    Route::get('/mypage/profile', [UserController::class, 'showEditProfile']);

    // 商品出品画面の表示
    Route::get('/sell',[ItemController::class, 'showSell']);

    // 商品の出品
    Route::post('/sell/create', [ItemController::class, 'createSell']);

    // いいね機能の実装
    Route::post('/item/likes/{item_id}', [ItemController::class, 'addLike']);

    // コメント機能の実装
    Route::post('item/comments/{item_id}', [ItemController::class, 'comment']);

    // プロフィール編集画面の更新
    Route::post('/mypage/profile/edit', [UserController::class, 'editProfile']);

    // 商品購入画面の表示
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchase']);

    // 住所変更画面の表示
    Route::get('/purchase/address/{item_id}',[PurchaseController::class, 'showAddress']);

    // 配送先住所の変更
    Route::post('/purchase/address/edit/{item_id}', [PurchaseController::class, 'editAddress']);

    // 商品の購入
    Route::post('/purchase/complete/{item_id}',[PurchaseController::class, 'createPurchase']);
});