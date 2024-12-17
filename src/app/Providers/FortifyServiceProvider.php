<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\CustomCreateNewUser;
use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\CustomVerifyEmailResponse;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ユーザ登録後にログイン画面に遷移するように設定
        $this->app->singleton(
            RegisteredUserController::class,
            RegisterController::class
        );

        // ログイン後は商品一覧画面に遷移するように設定
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CustomCreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Fortifyのデフォルトのルートをカスタムコントローラに置き換え
        Route::middleware(['web'])->post('/login', [\App\Http\Controllers\LoginController::class, 'store'])->name('login');

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        // メール認証未完了時のリダイレクト
        Fortify::verifyEmailView(function () {
            session()->flash('message', 'メール認証を完了してください。');

            return view('auth.login');
        });
    }
}
