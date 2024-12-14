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
use App\Models\Like;
use Database\Seeders\ExhibitionsTableSeeder;

class MyListView extends TestCase
{
    use RefreshDatabase;

    // マイリスト一覧取得 - いいねした商品だけが表示される
    public function testMyListView()
    {
        $this->seed(ExhibitionsTableSeeder::class);

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $exhibition = Exhibition::first();
        Like::create([
            'exhibition_id' => $exhibition->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSeeText($exhibition->name);

        $notLikedExhibition = Exhibition::where('id', '!=', $exhibition->id)->first();
        $response->assertDontSeeText($notLikedExhibition->name);
    }

    // マイリスト一覧取得 - 購入済みの商品は「sold」と表示
    public function testMyListPurchaseExhibition()
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

        $exhibition = Exhibition::first();
        Like::create([
            'exhibition_id' => $exhibition->id,
            'user_id' => $user->id,
        ]);

        Purchase::create([
            'exhibition_id' => $exhibition->id,
            'user_id' => $user->id,
            'address_id' => $address->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSeeText('Sold');

        $notLikedExhibition = Exhibition::where('id', '!=', $exhibition->id)->first();
        $notPurchasedExhibition = Exhibition::where('id', '!=', $exhibition->id)->first();
        Like::create([
            'exhibition_id' => $notPurchasedExhibition->id,
            'user_id' => $user->id,
        ]);

        $response->assertDontSeeText($notPurchasedExhibition->name . ' Sold');
    }

    // マイリスト一覧取得 - 自分が出品した商品は表示されない
    public function testMyListExhibitionWithoutMyself()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibitions = Exhibition::all();

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

        $userExhibition = $exhibitions->get(0);
        $otherUserExhibition = $exhibitions->get(1);

        Sale::create([
            'user_id' => $user->id,
            'exhibition_id' => $userExhibition->id,
        ]);

        Sale::create([
            'user_id' => $otherUser->id,
            'exhibition_id' => $otherUserExhibition->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'exhibition_id' => $otherUserExhibition->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertDontSee($userExhibition->name);
        $response->assertSee($otherUserExhibition->name);
    }

    // マイリスト一覧取得 - 未認証の場合は何も表示されない
    public function testMyListViewWithoutAuth()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibitions = Exhibition::All();

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        foreach($exhibitions as $exhibition){
            $response->assertDontSee($exhibition->name);
        }
    }
}
