<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUser extends TestCase
{
    use RefreshDatabase;

    // 会員登録 - 名前（入力なし）
    public function testRegisterName()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
        $response->assertSessionHas('errors');

        $errors = session('errors')->get('name');
        $this->assertContains('お名前を入力してください', $errors);
    }

    // 会員登録 - メールアドレス（入力なし）
    public function testRegisterEmail()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSessionHas('errors');

        $errors = session('errors')->get('email');
        $this->assertContains('メールアドレスを入力してください', $errors);
    }

    // 会員登録 - パスワード（入力なし）
    public function testRegisterPassword()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');

        $errors = session('errors')->get('password');
        $this->assertContains('パスワードを入力してください', $errors);
    }

    // 会員登録 - パスワード（文字数制限）
    public function testRegisterPasswordCount()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'word123',
            'password_confirmation' => 'word123',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');

        $errors = session('errors')->get('password');
        $this->assertContains('パスワードは8文字以上で入力してください', $errors);
    }

    // 会員登録 - パスワード（不一致）
    public function testRegisterPasswordConfirm()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');

        $errors = session('errors')->get('password');
        $this->assertContains('パスワードと一致しません', $errors);
    }

    // 会員登録 - 正常確認
    public function testRegister()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasNoErrors();
    }
}
