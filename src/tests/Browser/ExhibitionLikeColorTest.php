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
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

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
            $user->markEmailAsVerified();
            $this->assertTrue($user->hasVerifiedEmail());

            $browser->visit('/item/' . $exhibition->id)
            ->assertPresent('.likes__mark--button')
            ->screenshot('change_likes__mark-before')
            ->waitFor('.likes__mark--button')
            ->assertMissing('.liked')
            ->assertAttributeContains('.fa-star', 'class', 'fa-regular')
            ->press('.likes__mark--button')
            ->pause(500)
            ->assertAttributeContains('.fa-star', 'class', 'fa-solid')
            ->screenshot('change_likes__mark-after')
            ->assertVisible('.liked')
            ->press('.likes__mark--button')
            ->pause(500)
            ->assertMissing('.liked')
            ->assertAttributeContains('.fa-star', 'class', 'fa-regular');
        });
    }
}
