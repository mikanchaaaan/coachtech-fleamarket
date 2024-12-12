<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Exhibition;
use App\Models\Like;
use App\Models\User;
use Database\Seeders\ExhibitionsTableSeeder;

class ExhibitionSearch extends TestCase
{
    use RefreshDatabase;

    // 商品検索機能 - 「商品名」で部分一致検索ができる
    public function testExhibitionNameSearch()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition_clock = Exhibition::first();
        $exhibition_hdd = Exhibition::all()->get(1);

        $keyword = '時計';
        $response = $this->get('/?tab=all&keyword=' . $keyword);
        $response->assertStatus(200);

        $response->assertSee($exhibition_clock->name);
        $response->assertDontsee($exhibition_hdd->name);
    }

    // 商品検索機能 - 検索状態がマイリストでも保持されている
    public function testExhibitionNameSearchMyList()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition_clock = Exhibition::first();
        $exhibition_hdd = Exhibition::all()->get(1);

        $keyword = '時計';
        $response = $this->get('/?tab=all&keyword=' . $keyword);
        $response->assertStatus(200);

        $response->assertSee($exhibition_clock->name);
        $response->assertDontsee($exhibition_hdd->name);


        $user = User::create([
            'name' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        Like::create([
            'user_id' => $user->id,
            'exhibition_id' => $exhibition_clock->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'exhibition_id' => $exhibition_hdd->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist&keyword=' . $keyword);
        $response->assertStatus(200);

        $response->assertSee($exhibition_clock->name);
        $response->assertDontsee($exhibition_hdd->name);
    }
}
