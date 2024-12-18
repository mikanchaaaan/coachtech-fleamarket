<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use App\Models\Exhibition;
use App\Models\User;
use App\Models\Address;
use Tests\DuskTestCase;
use Database\Seeders\ExhibitionsTableSeeder;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;

    // 支払い方法選択機能 - 小計画面で変更が即時反映される
    public function testPaymentMethodUpdate()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $item_id = $exhibition->id;

        $this->browse(function (Browser $browser) use ($user, $exhibition) {
            $browser->visit('/login')
                ->type('login', $user->email)
                ->type('password', 'password123')
                ->press('ログインする')
                ->assertAuthenticatedAs($user);
            $user->markEmailAsVerified();
            $this->assertTrue($user->hasVerifiedEmail());

            $browser->visit('/purchase/'. $exhibition->id)
                ->assertSee('支払い方法')
                ->assertSee('選択してください')
                ->select('#payment', 'card_payment')
                ->waitForText('カード支払い')
                ->assertSeeIn('#display', 'カード支払い')
                ->select('#payment', 'convenience_payment')
                ->waitForText('コンビニ払い')
                ->assertSeeIn('#display', 'コンビニ払い');
                });
    }
}