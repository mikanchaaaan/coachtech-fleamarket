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
        $user = auth()->user();
        $user_id = auth()->check() ? auth()->id() : null;
        $tab = $request->query('tab', 'all');
        $keyword = $request->query('keyword', '');

        $exhibitions = collect();

        if ($keyword) {
            if ($tab == 'all') {
                $exhibitions = Exhibition::where('name', 'like', '%' . $keyword . '%')
                    ->whereDoesntHave('sales', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();
            } elseif ($tab == 'mylist') {
                if(auth()->check()){
                    /** @var \App\Models\User $user */
                    $exhibitions = $user->likedItems()->where('name', 'like', '%' . $keyword . '%')
                        ->whereDoesntHave('sales', function ($query) use ($user_id) {
                            $query->where('user_id', $user_id);
                        })
                        ->get();
                }
            }
        } else {
            if ($tab == 'all') {
                $exhibitions = Exhibition::whereDoesntHave('sales', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })->get();
            } elseif ($tab == 'mylist') {
                if(auth()->check()){
                    /** @var \App\Models\User $user */
                    $exhibitions = $user->likedItems()->whereDoesntHave('sales', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();
                }
            }
        }

        if ($tab == 'mylist' && !auth()->check()) {
            $exhibitions = collect();
        }

        session(['tab' => $tab]);

        return view('item.index', compact('exhibitions', 'tab', 'keyword'));
    }

    // 商品詳細画面の表示
    public function detail($item_id)
    {
        $exhibition = Exhibition::findOrFail($item_id);
        $user = auth()->user();

        $conditionLabels = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];

        $condition_label = $conditionLabels[$exhibition->condition];
        $isLiked = $exhibition->likes()->where('user_id', $user->id ?? 0)->exists();
        $countLikes = $exhibition->likes()->count();
        $comments = $exhibition->comments;
        $countComments = $exhibition->comments()->count();

        return view('item.detail', compact('exhibition', 'condition_label', 'countLikes','comments', 'isLiked', 'countComments'));
    }

    // 商品出品画面の表示
    public function showSell()
    {
        $categories = Category::all();
        return view('item.sell', compact('categories'));
    }

    // 商品の出品
    public function createSell(ExhibitionRequest $request)
    {
        $user = auth()->user();
        $exhibitionData = $request->only(['name','image','price','condition','description']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('exhibition_images', 'public');
            $exhibitionData['image'] = $path;
        }

        $exhibitionData['price'] = (int)str_replace(['￥', ','], '', $exhibitionData['price']);

        $exhibition = Exhibition::create($exhibitionData);

        $categories = $request->input('categories', []);

        foreach ($categories as $categoryId){
            $productData = [
                'exhibition_id' => $exhibition->id,
                'category_id' => $categoryId,
            ];
            Product::create($productData);
        }

        $saleData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
        ];
        Sale::create($saleData);

        return redirect("/");
    }

    // いいね機能の実装
    public function addLike($item_id)
    {
        $user = auth()->user();
        $exhibition = Exhibition::findOrFail($item_id);

        /** @var \App\Models\User $user */
        $existingLike = $user->likes()->where('exhibition_id', $exhibition->id)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
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
