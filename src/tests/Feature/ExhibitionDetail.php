<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ExhibitionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use App\Models\Exhibition;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Comment;

class ExhibitionDetail extends TestCase
{
    use RefreshDatabase;

    // 商品詳細情報取得 - 必要な情報が表示される
    public function testExhibitionViewDetail()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $exhibitions = Exhibition::all();
        $categories = Category::all();

        $user = User::create([
            'name' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        foreach($exhibitions as $exhibition) {

            $comment = Comment::create([
                'user_id' => $user->id,
                'exhibition_id' => $exhibition->id,
                'content' => 'おいしそうですね'
            ]);

            $category1 = Product::create([
                'exhibition_id' => $exhibition->id,
                'category_id' => 1,
            ]);

            $item_id = $exhibition->id;
            $response = $this->get('/item/' . $item_id);
            $response->assertStatus(200);

            $response->assertSee($exhibition->image);
            $response->assertSeeText($exhibition->name);
            $response->assertSeeText($exhibition->brand_name);
            $response->assertSeeText(number_format($exhibition->price));
            $response->assertSeeText($exhibition->countLikes);
            $response->assertSeeText($exhibition->countComments);
            $response->assertSeeText($exhibition->description);
            $response->assertSeeText($exhibition->condition_label);

            foreach ($exhibition->categories as $category) {
                $response->assertSeeText($category->content);
            }

            foreach ($exhibition->comments as $comment) {
                $response->assertSeeText($comment->user->name);
                $response->assertSeeText($comment->content);
            }
        }
    }

    // 商品詳細情報取得 - 複数選択されたカテゴリの表示
    public function testMultipleCategoriesView()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $exhibitions = Exhibition::all();
        $categories = Category::all();

        foreach($exhibitions as $exhibition){
            $category1 = Product::create([
                'exhibition_id' => $exhibition->id,
                'category_id' => $categories->get(0)->id,
            ]);

            $category2 = Product::create([
                'exhibition_id' => $exhibition->id,
                'category_id' => $categories->get(1)->id,
            ]);

            $category3 = Product::create([
                'exhibition_id' => $exhibition->id,
                'category_id' => $categories->get(2)->id,
            ]);

            $item_id = $exhibition->id;
            $response = $this->get('/item/' . $item_id);
            $response->assertStatus(200);

            foreach ($exhibition->categories as $category) {
                $response->assertSeeText($category->content);
            }
        }
    }
}
