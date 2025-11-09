<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ManageSessions;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FxPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('fx')
            ->path('fx')
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(false)
            // Keep using 'web' for now to avoid lockout.
            // Switch to 'fx' after creating an FX user and logging in via that guard.
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->brandName('Rebrandz FX')
            ->brandLogo('/assets/images/logolight.png')
            ->darkModeBrandLogo('/assets/images/logodark.png')
            ->breadcrumbs()
            ->topNavigation()
            ->maxContentWidth(Width::Full)
            ->userMenuItems([
                Action::make('backHome')
                    ->label('Admin Panel')
                    ->url('/admin')
                    ->visible(fn (): bool => in_array(Auth::user()->user_type, ['admin', 'super admin', 'system']))
                    ->icon(Heroicon::OutlinedHome),
                Action::make('browserSessions')
                    ->label('My Sessions')
                    ->url(fn (): string => ManageSessions::getUrl(panel: 'fx'))
                    ->icon(Heroicon::OutlinedDevicePhoneMobile),
            ])
            ->colors([
                'gray' => Color::Gray,
                'primary' => Color::Indigo,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger' => Color::Rose,
                'critical' => 'rgb(185, 28, 28)',
            ])
            ->discoverResources(in: app_path('Filament/Fx/Resources'), for: 'App\Filament\Fx\Resources')
            ->discoverPages(in: app_path('Filament/Fx/Pages'), for: 'App\Filament\Fx\Pages')
            ->pages([
                Dashboard::class,
                ManageSessions::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Fx/Widgets'), for: 'App\Filament\Fx\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            // No Shield plugin; access is governed by user_type rules
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
