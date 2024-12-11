<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // 7. ログイン機能 - メールアドレス未入力
    public function testLoginEmail()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('login', [
            'login' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');

        $errors = session('errors')->get('login');
        $this->assertContains('メールアドレスを入力してください', $errors);
    }

    // 8. ログイン機能 - パスワード未入力
    public function testLoginPassword()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('login', [
            'login' => 'test',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');

        $errors = session('errors')->get('password');
        $this->assertContains('パスワードを入力してください', $errors);
    }

    // 9. ログイン機能 - 入力不正
    public function testLoginInvalid()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post('login', [
            'login' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('login');
        $errors = session('errors')->get('login');
        $this->assertContains('ログイン情報が登録されていません。', $errors);
    }

    // 10. ログイン機能 - 正常確認
    public function testLogin()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
    }
}
