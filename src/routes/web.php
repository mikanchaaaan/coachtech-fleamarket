<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('welcome');
});


// プロフィール編集画面の表示
Route::get('/mypage/profile', [UserController::class, 'showEditProfile']);

// プロフィール編集画面の更新
Route::post('/mypage/profile/edit', [UserController::class, 'editProfile']);