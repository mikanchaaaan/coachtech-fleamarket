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

class ChangeAddress extends TestCase
{
    use RefreshDatabase;

    // 配送先変更機能 - 送付先変更画面にて登録した住所が商品購入画面に反映されている
    public function testChangeAddressView()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $beforeAddress = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $this->actingAs($user);
        $item_id = $exhibition->id;
        $response = $this->get('/purchase/address/' . $item_id);
        $response->assertStatus(200);

        $changeAddressData = [
            'postcode' => '234-5678',
            'address' => '456 Sub Street',
            'building' => 'change-Building'
        ];

        $response = $this->post("/purchase/address/edit/{$item_id}", $changeAddressData);
        $response->assertRedirect('/purchase/' . $item_id);

        $response = $this->get('/purchase/' . $item_id);
        $response->assertStatus(200);

        $response->assertSeeText($changeAddressData['postcode']);
        $response->assertSeeText($changeAddressData['address']);
        $response->assertSeeText($changeAddressData['building']);

        $response->assertDontSeeText($beforeAddress->postcode);
        $response->assertDontSeeText($beforeAddress->address);
        $response->assertDontSeeText($beforeAddress->building);
    }

    // 配送先変更機能 - 購入した商品に送付先住所が紐づいて登録される
    public function testRegisterAddress()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $beforeAddress = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $this->actingAs($user);
        $item_id = $exhibition->id;
        $response = $this->get('/purchase/address/' . $item_id);
        $response->assertStatus(200);

        $changeAddressData = [
            'postcode' => '234-5678',
            'address' => '456 Sub Street',
            'building' => 'change-Building'
        ];

        $response = $this->post("/purchase/address/edit/{$item_id}", $changeAddressData);

        $purchaseData = [
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $user->address->id,
            'payment-method' => 'convenience_payment'
        ];

        $response = $this->actingAs($user)->post("/purchase/complete/{$item_id}", $purchaseData);

        $this->assertDatabaseHas('purchases', [
            'exhibition_id' => $item_id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'postcode' => $changeAddressData['postcode'],
            'address' => $changeAddressData['address'],
            'building' => $changeAddressData['building'],
        ]);
    }
}
