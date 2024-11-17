<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

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
Route::get('/', [ItemController::class, 'index']);

// 商品詳細画面の表示
Route::get('/item/{item_id}', [ItemController::class, 'detail']);

Route::middleware('auth')->group(function () {
    // プロフィール編集画面の表示
    Route::get('/mypage/profile', [UserController::class, 'showEditProfile']);

    // プロフィール編集画面の更新
    Route::post('/mypage/profile/edit', [UserController::class, 'editProfile']);

    // 商品購入画面の表示
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchase']);

    // 住所変更画面の表示
    Route::get('/purchase/address/{item_id}',[PurchaseController::class, 'showAddress']);

    // 配送先住所の変更
    Route::post('/purchase/address/edit/{item_id}', [PurchaseController::class, 'editAddress']);

});