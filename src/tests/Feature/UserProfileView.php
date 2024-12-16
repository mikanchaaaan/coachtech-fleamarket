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

class UserProfileView extends TestCase
{
    use RefreshDatabase;

    // ユーザ情報取得 - プロフィール画面で必要な情報が取得できる
    public function testMyProfileView()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'Test user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'image' => 'xxx.png'
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $userSellExhibition = Sale::create([
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
        ]);

        $userPurchaseExhibition = Purchase::create([
            'user_id' => $user->id,
            'exhibition_id' => $exhibition->id,
            'address_id' => $user->address->id,
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);

        $response = $this->get('/mypage');
        $response->assertStatus(200);

        $response->assertSee($user->image);
        $response->assertSeeText($user->name);
        $response->assertSeeText($userSellExhibition->name);
        $response->assertSeeText($userPurchaseExhibition->name);
    }
}