<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\LoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $request = request();

        if ($request->is('admin/*')) {
            config([
                'fortify.guard' => 'admin',
                'fortify.passwords' => 'admins',
                'fortify.prefix' => 'admin',
                'fortify.home' => '/dashboard',
            ]);
        }
//        $this->app->instance(LoginResponse::class, new class extends LoginResponse {
//            public function toResponse($request)
//            {
//                return redirect('/');
//            }
//        });
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            if ($request->is('admin/*')) {
                $user = Admin::where('email', $request->email)->first();
            } else {
                $user = User::where('email', $request->email)->first();
            }

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . '|' . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return request()->is('admin/*')
                ? view('auth.admin_login')
                : view('auth.login');
        });

        // Add other views as needed
    }
}
