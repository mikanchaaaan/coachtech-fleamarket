<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Exhibition;
use App\Models\Like;
use Database\Seeders\ExhibitionsTableSeeder;

class ExhibitionLike extends TestCase
{
    use RefreshDatabase;

    // いいね機能 - いいね商品の登録
    public function testExhibitionLike()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);
        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $initialLikesCount = $exhibition->likes()->count();

        $response = $this->actingAs($user)->post('/item/likes/' . $item_id , [
            'exhibition_id' => $exhibition->id,
        ]);

        $exhibition->refresh();
        $this->assertEquals($initialLikesCount + 1, $exhibition->likes()->count());
    }

}
