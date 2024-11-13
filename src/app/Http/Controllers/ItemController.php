<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;

class ItemController extends Controller
{
    // 商品一覧画面の表示
    public function index()
    {
        $exhibitions = Exhibition::all();
        return view('item.index', compact('exhibitions'));
    }
}
