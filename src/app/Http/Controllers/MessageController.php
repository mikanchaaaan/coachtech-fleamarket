<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;

class MessageController extends Controller
{
    // 取引チャット画面の表示
    public function showMessage($item_id){
        $exhibition = Exhibition::findOrFail($item_id);
        $user = auth()->user();

        return view('user.message', compact('exhibition', 'user'));
    }
}
