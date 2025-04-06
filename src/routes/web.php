<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MessageController;

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

Route::middleware(['auth'])->group(function () {
    // プロフィール編集画面の表示
    Route::get('/mypage/profile', [UserController::class, 'showEditProfile']);

    // プロフィール編集画面の更新
    Route::post('/mypage/profile/edit', [UserController::class, 'editProfile']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // プロフィール画面の表示
    Route::get('mypage', [UserController::class, 'showProfile']);

    // 商品出品画面の表示
    Route::get('/sell',[ItemController::class, 'showSell']);

    // 商品の出品
    Route::post('/sell/create', [ItemController::class, 'createSell']);

    // いいね機能の実装
    Route::post('/item/likes/{item_id}', [ItemController::class, 'addLike']);

    // コメント機能の実装
    Route::post('item/comments/{item_id}', [ItemController::class, 'comment']);

    // 商品購入画面の表示
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchase']);

    // 住所変更画面の表示
    Route::get('/purchase/address/{item_id}',[PurchaseController::class, 'showAddress']);

    // 配送先住所の変更
    Route::post('/purchase/address/edit/{item_id}', [PurchaseController::class, 'editAddress']);

    // 商品の購入（Stripe決済）
    Route::post('/checkout/{item_id}', [PurchaseController::class, 'checkout']);

    // Stripe決済成功
    Route::get('/checkout/success', [PurchaseController::class, 'success'])->name('checkout.success');

    // Stripe決済失敗
    Route::get('/checkout/cancel', [PurchaseController::class, 'cancel'])->name('checkout.cancel');

    // 要件追加で追記_202504
    // 取引チャット画面の表示
    Route::get('/message/{item_id}', [MessageController::class, 'showMessage']);

    // 未読メッセージを既読に変更
    Route::post('/message/{id}/mark-as-read', [MessageController::class, 'markAsRead']);

    // 未読メッセージのカウント
    Route::get('/api/unread-message-count', [UserController::class, 'getUnreadMessageCount']);

    // メッセージ送信
    Route::post('/message/send', [MessageController::class, 'sendMessage']);

    // メッセージ編集
    Route::post('/message/{id}/edit', [MessageController::class, 'updateMessage']);

    // メッセージ削除
    Route::post('/message/{id}/delete', [MessageController::class, 'destroyMessage']);

    // 取引相手の評価
    Route::post('/transaction/rate/{exhibition_id}' , [MessageController::class, 'transactionReview']);
});

// メール認証後のリダイレクト先指定
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['signed'])->name('verification.verify');