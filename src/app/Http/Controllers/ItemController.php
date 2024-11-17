<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    // 商品一覧画面の表示
    public function index()
    {
        $exhibitions = Exhibition::all();
        return view('item.index', compact('exhibitions'));
    }

    // 商品詳細画面の表示
    public function detail($item_id)
    {
        $exhibition = Exhibition::findOrFail($item_id);

        // 商品の状態に基づく状態名を決定
        $conditionLabels = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];

        // 状態が不明の場合の処理
        $condition = $conditionLabels[$exhibition->condition] ?? '不明';

        return view('item.detail', compact('exhibition', 'condition'));
    }
}
