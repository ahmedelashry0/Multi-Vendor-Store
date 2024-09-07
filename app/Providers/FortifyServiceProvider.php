<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No request-dependent logic should go here.
        if (request()->is('admin/*')) {
            config()->set('fortify.prefix' , 'admin');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Guard and home configuration based on request path
        if (request()->is('admin/*')) {
            config()->set('fortify.guard', 'admin');
            config()->set('fortify.passwords', 'admins');
            Config::set('auth.defaults.guard','admin');
            dump(config()->get('fortify.guard'));
        }

        // Registering the action classes for Fortify
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Rate limiter for login attempts
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . '|' . $request->ip());
        });

        // Rate limiter for two-factor authentication
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Registering custom views for Fortify's routes
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::loginView(fn() => view('auth.login'));
        // Add more views like password reset, email verification as needed
    }
}
