<?php

namespace App\Providers\Filament;

use App\Filament\Pages\LoginPage;
use Filament\Actions\Action;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use Filament\SpatieLaravelTranslatablePlugin;
use App\Filament\App\Resources\PathwayResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Facades\FilamentSocialite;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('')
            ->homeUrl('/home')
            ->topbar(false)
            ->login(LoginPage::class)
            ->profile()
            ->passwordReset()
            ->registration()
            ->darkMode(false)
            ->colors([
                'primary' => Color::hex('#383B6D'),
                'stats4sd' => Color::hex('#D32229'),
                'darkblue' => Color::hex('#383B6D'),
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->font('Open Sans')
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->navigation(false)
            ->plugin(
                FilamentSocialitePlugin::make()
                    ->setProviders([
                        'google' => [
                            'label' => 'Google',
                            'icon' => 'fab-google'
                        ],
                        'linkedin' => [
                            'icon' => 'fab-linkedin',
                            'label' => 'LinkedIn',
                        ],
                        'github' => [
                            'label' => 'GitHub',
                            'icon' => 'fab-github',
                            // 'color' => 'primary',
                            'outlined' => false,
                        ],
                        'facebook' => [
                            'icon' => 'fab-facebook',
                            'label' => 'Facebook',
                        ],
                    ])
                    ->setRegistrationEnabled(true)
            )
            ->authMiddleware([
                // Authenticate::class,
            ])
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'es', 'fr'])
            )
            ->renderHook(
                'panels::body.start',
                fn () => view('header'),
            )
            ->renderHook(
                'panels::footer',
                fn () => view('footer'),
            );
    }
}
