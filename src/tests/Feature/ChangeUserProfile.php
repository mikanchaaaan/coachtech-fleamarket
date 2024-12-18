<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Address;

class ChangeUserProfile extends TestCase
{
    use RefreshDatabase;

    // ユーザー情報変更 - プロフィール変更時に既存の情報が初期値として表示されていること
    public function testMyProfileEdit()
    {
        $user = User::create([
            'name' => 'Test user',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'image' => UploadedFile::fake()->image('profile.png')
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '123 Main Street',
            'building' => 'building'
        ]);

        $response = $this->post('login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $user->markEmailAsVerified(); // メール認証を強制的に完了させる
        $this->assertTrue($user->hasVerifiedEmail()); // メール認証が完了していることを確認

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee($user->image);
        $response->assertSee($user->name);
        $response->assertSee($user->address->postcode);
        $response->assertSee($user->address->address);
        $response->assertSee($user->address->building);

        $updateProfile = [
            'image' => UploadedFile::fake()->image('profile_updated.png'),
            'name' => 'Update User',
            'postcode' => '234-5678',
            'address' => '456 Sub Street',
            'building' => 'Updated-Building'
        ];

        $response = $this->post("/mypage/profile/edit", $updateProfile);
        $response->assertRedirect('/mypage');
    }
}
