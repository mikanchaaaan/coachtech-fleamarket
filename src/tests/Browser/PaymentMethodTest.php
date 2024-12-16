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

        // テスト用ユーザーを作成
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

            // 任意の購入ページにアクセス
            $browser->visit('/purchase/'. $exhibition->id) // URLはアプリの実装に合わせる
                ->assertSee('支払い方法') // ページが正しく表示されているか確認
                ->assertSee('選択してください') // 初期状態の確認
                ->select('#payment', 'card_payment') // 支払い方法を「カード支払い」に変更
                ->waitForText('カード支払い') // 表示が変更されるまで待機
                ->assertSeeIn('#display', 'カード支払い') // 表示が正しいか確認
                ->select('#payment', 'convenience_payment') // 支払い方法を「コンビニ払い」に変更
                ->waitForText('コンビニ払い') // 表示が変更されるまで待機
                ->assertSeeIn('#display', 'コンビニ払い'); // 表示が正しいか確認
                });
    }
}