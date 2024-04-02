<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Clusters\Competencies;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\ModuleResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\PathwayResource;
use App\Filament\Resources\SectionResource;
use App\Filament\Resources\ActivityResource;
use Filament\SpatieLaravelTranslatablePlugin;
use App\Filament\Resources\CompetencyResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\ResearchComponentResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Filament\Resources\CompetencyCategoryResource;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->homeUrl('/home')
            ->brandName('RM Syllabus')
            ->login()
            ->profile()
            ->passwordReset()
            ->registration()
            ->emailVerification()
            ->darkMode(false)
            ->colors([
                'primary' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                ->groups([
                    NavigationGroup::make('')
                        ->items([
                            ...ResearchComponentResource::getNavigationItems(),
                            ...ModuleResource::getNavigationItems(),
                            ...SectionResource::getNavigationItems(),
                            ...ActivityResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('')
                        ->items([
                            ...PathwayResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('')
                        ->items([
                            ...Competencies::getNavigationItems(),
                        ]),
                    NavigationGroup::make('')
                        ->items([
                            ...UserResource::getNavigationItems(),
                        ]),
                ]);
            })
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'es', 'fr'])
                );
    }
}
