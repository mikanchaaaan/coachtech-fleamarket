<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Exhibition;
use App\Models\Purchase;
use App\Models\Sale;
use Database\Seeders\ExhibitionsTableSeeder;

class ExhibitionListView extends TestCase
{
    use RefreshDatabase;

    // 商品一覧取得 - 全商品取得
    public function testViewExhibitions()
    {
        $this->seed(ExhibitionsTableSeeder::class);

        $response = $this->get('/');
        $response->assertStatus(200);

        $exhibitions = Exhibition::all();
        foreach ($exhibitions as $exhibition) {
            $response->assertSeeText($exhibition->name);
        }
    }

    // 商品一覧取得 - 購入済みの商品はsoldと表示
    public function testPurchaseExhibition()
    {
        $this->seed(ExhibitionsTableSeeder::class);

        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京',
            'building' => '丸の内ビル',
        ]);

        $exhibition = Exhibition::all();
        $purchasedExhibition = $exhibition->first();
        Purchase::create([
            'exhibition_id' => $purchasedExhibition->id,
            'user_id' => $user->id,
            'address_id' => $address->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSeeText('Sold');

        $notPurchasedExhibition = Exhibition::where('id', '!=', $purchasedExhibition->id)->first();
        $response->assertDontSeeText($notPurchasedExhibition->name . ' Sold');
    }

    // 商品一覧取得 - 自分が出品した商品は表示しない
    public function testViewExhibitionsWithoutMyself()
    {
        $user = User::create([
            'name' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $otherUser = User::create([
            'name' => 'other_user',
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $userExhibition = Exhibition::create([
            'condition' => 2,
            'name' => 'HDD',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'brand_name' => '',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
        ]);

        $otherUserExhibition = Exhibition::create([
            'condition' => 3,
            'name' => '玉ねぎ3束',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'brand_name' => '',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
        ]);

        $userSales = Sale::create([
            'user_id' => $user->id,
            'exhibition_id' => $userExhibition->id,
        ]);

        $otherUserSales = Sale::create([
            'user_id' => $otherUser->id,
            'exhibition_id' => $otherUserExhibition->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee($userExhibition->name);
        $response->assertSee($otherUserExhibition->name);
    }
}
