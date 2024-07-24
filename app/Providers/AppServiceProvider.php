<?php

namespace App\Providers;

use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DutchCodingCompany\FilamentSocialite\Facades\FilamentSocialite;
use Illuminate\Support\ServiceProvider;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, \App\Http\Responses\LoginResponse::class);
        $this->app->singleton(RegistrationResponse::class, \App\Http\Responses\RegistrationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Translatable::fallback(
            fallbackAny: true,
       );

        // Overwrite how users are redirected after logging in through Socialite
        FilamentSocialite::setLoginRedirectCallback(function (string $provider, FilamentSocialiteUserContract $socialiteUser) {

            $intended = session()->pull('intended_url', '/');

            return redirect()->intended($intended);
        });

    }


}
