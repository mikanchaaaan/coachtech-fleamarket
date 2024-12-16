<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Exhibition;
use Database\Seeders\CategoriesTableSeeder;

class ExhibitionSale extends TestCase
{
    use RefreshDatabase;

    // 出品商品情報登録 - 商品出品画面にて必要な情報が保存できること
    public function testSaleExhibitionRegister()
    {
        $user = User::create([
            'name' => 'Test user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'image' => 'profile.png'
        ]);

        $this->seed(CategoriesTableSeeder::class);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);


        $response = $this->get('/sell');
        $response->assertStatus(200);

        $sellExhibitionData = [
            'name' => 'sellExhibition',
            'image' => UploadedFile::fake()->image('exhibition.png'),
            'brand_name' => 'Exhibition_brand',
            'price' => '15000',
            'condition' => 1,
            'description' => 'テスト商品の登録',
            'categories' => [1, 4],
        ];

        $response = $this->post("/sell/create", $sellExhibitionData);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('exhibitions', ['name' => 'sellExhibition']);
        $this->assertDatabaseHas('products', [
            'exhibition_id' => Exhibition::latest()->first()->id,
            'category_id' => 1,
        ]);
        $this->assertDatabaseHas('sales', [
            'user_id' => $user->id,
            'exhibition_id' => Exhibition::latest()->first()->id,
        ]);
    }
}
