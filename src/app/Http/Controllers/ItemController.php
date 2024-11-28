<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Like;
use App\Models\User;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
// 商品一覧画面の表示
public function index(Request $request)
{
    // ログイン中のユーザIDの取得
    $user = auth()->user();
    $user_id = auth()->check() ? auth()->id() : null;

    // タブ情報の確認
    $tab = $request->query('tab', 'all'); // デフォルトは 'all'

    // 検索情報の取得
    $keyword = $request->query('keyword', ''); // 検索キーワードの取得

    // 商品取得の初期化
    $exhibitions = collect(); // 最初に空のコレクションを用意

    // 検索条件がある場合、キーワードで絞り込み
    if ($keyword) {
        // allタブの表示
        if ($tab == 'all') {
            $exhibitions = Exhibition::where('name', 'like', '%' . $keyword . '%')
                ->whereDoesntHave('sales', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->get();
        }
        // mylistタブの表示
        elseif ($tab == 'mylist') {
            if(auth()->check()){
                /** @var \App\Models\User $user */
                $exhibitions = $user->likedItems()->where('name', 'like', '%' . $keyword . '%')
                    ->whereDoesntHave('sales', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();
            } else {
                return redirect()->route('login');
            }
        }
    } else {
        // 検索条件がない場合
        // allタブの表示
        if ($tab == 'all') {
            $exhibitions = Exhibition::whereDoesntHave('sales', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get();
        // mylistタブの表示
        } elseif ($tab == 'mylist') {
            if(auth()->check()){
                /** @var \App\Models\User $user */
                $exhibitions = $user->likedItems()->whereDoesntHave('sales', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->get();
            } else {
                return redirect()->route('login');
            }
        }
    }
    session(['tab' => $tab]);

    return view('item.index', compact('exhibitions', 'tab', 'keyword'));
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

        // いいね数のカウント
        $countLikes = $exhibition->likes()->count();

        // コメントの表示
        $comments = $exhibition->comments;

        // コメント数のカウント
        $countComments = $exhibition->comments()->count();

        return view('item.detail', compact('exhibition', 'condition', 'countLikes','comments', 'countComments'));
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
        $exhibitionData['price'] = (int)str_replace(['￥', ','], '', $exhibitionData['price']);

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

    // いいね機能の実装
    public function addLike($item_id)
    {
        $user = auth()->user();
        $exhibition = Exhibition::findOrFail($item_id);

        // すでに「いいね」しているかチェック
        /** @var \App\Models\User $user */
        $existingLike = $user->likes()->where('exhibition_id', $exhibition->id)->first();

        if ($existingLike) {
            // すでにいいねしていたら削除
            $existingLike->delete();
        } else {
            // まだいいねしていなければ、新規作成
            /** @var \App\Models\User $user */
            $user->likes()->create([
                'exhibition_id' => $exhibition->id,
            ]);
        }

        return back();
    }

    // コメント機能の実装
    public function comment(CommentRequest $request, $item_id)
    {
        $exhibition = Exhibition::findOrFail($item_id);

        Comment::create([
            'user_id' => auth()->id(),
            'exhibition_id' => $exhibition->id,
            'content' => $request->input('content'),
        ]);

        return back();
    }
}
