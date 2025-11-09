<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ManageSessions;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Rebrandz FX')
            ->brandLogo('/assets/images/logolight.png')
            ->darkModeBrandLogo('/assets/images/logodark.png')
            ->login(false)
            // ->registration()
            // ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
                        ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\ManageSessions::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->userMenuItems([
                Action::make('fxPanel')
                    ->label('FX Panel')
                    ->url('/fx')
                    ->visible(fn (): bool => in_array(Auth::user()->user_type, ['admin', 'super admin', 'system']))
                    ->icon(Heroicon::OutlinedBuildingOffice),
                Action::make('browserSessions')
                    ->label('My Sessions')
                    ->url(fn (): string => ManageSessions::getUrl(panel: 'admin'))
                    ->icon(Heroicon::OutlinedDevicePhoneMobile),
                Action::make('pulse')
                    ->label('Pulse')
                    ->url(fn (): string => url(config('pulse.path', 'pulse')))
                    ->icon(Heroicon::OutlinedChartBar)
                    ->visible(fn (): bool => Gate::allows('viewPulse')),
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
