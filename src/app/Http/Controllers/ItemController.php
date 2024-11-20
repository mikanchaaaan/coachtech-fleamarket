<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Sale;
use App\Models\Product;

class ItemController extends Controller
{
    // 商品一覧画面の表示
    public function index()
    {
        // 商品情報と購入情報の取得
        $exhibitions = Exhibition::with('purchases')->get();
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

    // 商品出品画面の表示
    public function showSell()
    {
        $categories = Category::all();
        return view('item.sell', compact('categories'));
    }

    // 商品の出品
    public function createSell(Request $request)
    {
        // ログイン中のユーザの確認
        $user = auth()->user();

        // 商品情報の取得
        $exhibitionData = $request->only(['name','image','price','condition','description']);

        // 画像をstorageディレクトリに保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('exhibition_images', 'public'); // 画像を保存
            $exhibitionData['image'] = $path; // パスを保存用データに追加
        }

        // 価格から￥記号を取り除き、整数に変換
        $exhibitionData['price'] = (int)str_replace('￥', '', $exhibitionData['price']);

        // Exhibitionテーブルに商品の登録
        $exhibition = Exhibition::create($exhibitionData);

        // Productテーブルに登録するデータを作成
        $categories = $request->input('categories', []);

        foreach ($categories as $categoryId){
            $productData = [
                'exhibition_id' => $exhibition->id,
                'category_id' => $categoryId,
            ];

            // Productテーブルに商品とカテゴリーの紐づけを登録
            Product::create($productData);
        }

        // Saleテーブルに登録するデータを作成
        $saleData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
        ];

        // Saleテーブルに出品情報の登録
        Sale::create($saleData);

        // 商品一覧にリダイレクト
        return redirect("/")->with('message','商品を追加しました');

    }
}
