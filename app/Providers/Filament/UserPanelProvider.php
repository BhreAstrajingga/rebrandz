<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AppsSettings;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\BillingInvoices;
use App\Filament\Pages\BillingPaymentMethods;
use App\Filament\Pages\CreateTenant;
use App\Filament\Pages\ManageSessions;
use App\Filament\Pages\ManageTenantMembers;
use App\Filament\Pages\Pricing;
use App\Filament\Pages\SubscriberHome;
use App\Filament\Pages\SupportTickets;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(false)
            ->registration(Register::class)
            ->passwordReset()
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->userMenuItems([
                Action::make('browserSessions')
                    ->label('My Sessions')
                    ->url(fn (): string => ManageSessions::getUrl(panel: 'user'))
                    ->icon(Heroicon::OutlinedDevicePhoneMobile),
            ])
            ->colors([
                'primary' => Color::Blue,
            ])
            // ->homeUrl(fn (): ?string => \App\Filament\Pages\SubscriberHome::getUrl(panel: 'user'))
            ->pages([
                \App\Filament\Pages\UserDashboard::class,
                CreateTenant::class,
                // SubscriberHome::class,
                Pricing::class,
                BillingInvoices::class,
                BillingPaymentMethods::class,
                AppsSettings::class,
                SupportTickets::class,
                ManageTenantMembers::class,
                
                \\App\\Filament\\Pages\\ManageBranches::class,
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
            ]);
    }
}
