<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Exhibition;
use App\Models\Like;
use Database\Seeders\ExhibitionsTableSeeder;
use Illuminate\Support\Facades\Log;

class ExhibitionLikeColorTest extends DuskTestCase
{
    use DatabaseMigrations;

    // いいね機能 - 追加済みのアイコンは色が変化する
    public function testExhibitionLikeColor()
    {
        Log::info('Current Environment: ' . env('APP_ENV'));
        Log::info('Database Connection: ' . env('DB_CONNECTION'));
        Log::info('Database Name: ' . env('DB_DATABASE'));

        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        // テスト用ユーザーを作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $savedUser = User::all()->first();
        Log::info('Saved User: ' . json_encode($savedUser));

        $this->browse(function (Browser $browser) use ($user, $exhibition) {
            $browser->visit('/login')
                ->type('login', $user->email)
                ->type('password', 'password123')
                ->press('ログインする')
                ->assertAuthenticatedAs($user);

            // 商品詳細ページに移動して、テストを実行
            $browser->visit('/item/' . $exhibition->id)
            ->assertPresent('.likes__mark--button')
            ->waitFor('.likes__mark--button')
            ->assertMissing('.liked')
            ->assertAttributeContains('.fa-star', 'class', 'fa-regular')
            ->press('.likes__mark--button')
            ->pause(500)
            ->assertAttributeContains('.fa-star', 'class', 'fa-solid')
            ->assertVisible('.liked')
            ->press('.likes__mark--button')
            ->pause(500)
            ->assertMissing('.liked')
            ->assertAttributeContains('.fa-star', 'class', 'fa-regular');
        });
    }
}
