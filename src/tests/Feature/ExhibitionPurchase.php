<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Exhibition;
use App\Models\Purchase;
use Database\Seeders\ExhibitionsTableSeeder;

class ExhibitionPurchase extends TestCase
{
    use RefreshDatabase;

    // 商品購入機能 - 「購入する」ボタンを押すと購入が完了する
    public function testCompletePurchase()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $this->actingAs($user);
        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('purchases', [
            'exhibition_id' => $exhibition->id,
        ]);

        $purchaseData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
            'payment-method' => 'convenience_payment'
        ];

        $response = $this->actingAs($user)->post("/purchase/complete/{$item_id}", $purchaseData);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
        ]);

        $response->assertRedirect('/mypage');
    }

    // 商品購入機能 - 購入した商品は商品一覧にて「sold」と表示
    public function testPurchaseViewSold()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $this->actingAs($user);
        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('purchases', [
            'exhibition_id' => $exhibition->id,
        ]);

        $purchaseData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
            'payment-method' => 'convenience_payment'
        ];

        $response = $this->actingAs($user)->post("/purchase/complete/{$item_id}", $purchaseData);

        $response = $this->get('/');
        $response->assertStatus(200);

        $purchasedExhibition = Exhibition::find($purchaseData['exhibition_id']);
        $response->assertSeeText($purchasedExhibition->name);
        $response->assertSeeInOrder([$purchasedExhibition->name, 'Sold']);

        $notPurchasedExhibition = Exhibition::where('id', '!=', $purchasedExhibition->id)->first();
        $response->assertSeeText($notPurchasedExhibition->name);
        $response->assertDontSeeText($notPurchasedExhibition->name . ' Sold');
    }

    // 商品購入機能 - プロフィール画面の購入一覧に商品が追加されている
    public function testPurchaseViewMyPage()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $this->actingAs($user);
        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('purchases', [
            'exhibition_id' => $exhibition->id,
        ]);

        $purchaseData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $address->id,
            'payment-method' => 'convenience_payment'
        ];

        $response = $this->actingAs($user)->post("/purchase/complete/{$item_id}", $purchaseData);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage?tab=buy');
        $response->assertStatus(200);

        $purchasedExhibition = Purchase::where('user_id', $purchaseData['user_id'])
        ->where('exhibition_id', $purchaseData['exhibition_id'])
        ->first();
        $response->assertSeeText($purchasedExhibition->exhibition->name);

        $notPurchasedExhibition = Exhibition::whereDoesntHave('purchases')->first();
        $response->assertDontSeeText($notPurchasedExhibition->name);
    }
}
