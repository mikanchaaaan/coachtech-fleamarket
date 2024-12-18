<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Exhibition;
use App\Models\Comment;
use Database\Seeders\ExhibitionsTableSeeder;

class ExhibitionComment extends TestCase
{
    use RefreshDatabase;

    // コメント送信機能 - ログイン済みのユーザーはコメントを送信できる
    public function testComment()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);
        $user->markEmailAsVerified(); // メール認証を強制的に完了させる
        $this->assertTrue($user->hasVerifiedEmail()); // メール認証が完了していることを確認

        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $initialCommentsCount = $exhibition->comments()->count();

        $response = $this->actingAs($user)->post('/item/comments/' . $item_id, [
            'exhibition_id' => $exhibition->id,
            'content' => 'かっこいいですね',
        ]);

        $exhibition->refresh();
        $this->assertEquals($initialCommentsCount + 1, $exhibition->comments()->count());
    }

    // コメント送信機能 - ログイン前のユーザーはコメントを送信できない
    public function testCommentWithoutLogin()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $response = $this->post('/item/comments/' . $item_id, [
            'exhibition_id' => $exhibition->id,
            'content' => 'かっこいいですね',
        ]);

        $response->assertRedirect('/login');

        $exhibition->refresh();
        $this->assertEquals(0, $exhibition->comments()->count());
    }

    // コメント送信機能 - コメントが入力されていない場合、バリデーションメッセージが表示
    public function testCommentWithoutMessage()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);
        $user->markEmailAsVerified(); // メール認証を強制的に完了させる
        $this->assertTrue($user->hasVerifiedEmail()); // メール認証が完了していることを確認

        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $response = $this->post('/item/comments/' . $item_id, [
            'exhibition_id' => $exhibition->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');

        $errors = session('errors')->get('content');
        $this->assertContains('コメントを入力してください', $errors);
    }

    // コメント送信機能 - コメントが256字以上の場合、バリデーションメッセージが表示
    public function testCommentOverCharacter()
    {
        $this->seed(ExhibitionsTableSeeder::class);
        $exhibition = Exhibition::first();

        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);
        $user->markEmailAsVerified(); // メール認証を強制的に完了させる
        $this->assertTrue($user->hasVerifiedEmail()); // メール認証が完了していることを確認

        $item_id = $exhibition->id;
        $response = $this->get('/item/' . $item_id);
        $response->assertStatus(200);

        $randomContent = Str::random(256);
        $response = $this->post('/item/comments/' . $item_id, [
            'exhibition_id' => $exhibition->id,
            'content' => $randomContent,
        ]);

        $response->assertSessionHasErrors('content');

        $errors = session('errors')->get('content');
        $this->assertContains('コメントは255文字以内で入力してください', $errors);
    }
}
