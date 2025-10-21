<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Carbon\Carbon;
use App\Models\BrowserSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Filament\Pages\Enums\UserMenuItem;
use Filament\Pages\UserMenuItem as UserMenuAction;

class ManageSessions extends Page implements HasTable
{
    use InteractsWithTable;
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static ?string $navigationLabel = 'My Sessions';

    protected static ?string $title = 'My Sessions';

    protected static ?string $slug = 'sessions';

    protected string $view = 'filament.pages.manage-sessions';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void {}

    public function table(Table $table): Table
    {
        $query = config('session.driver') === 'database'
            ? BrowserSession::query()
                ->where('user_id', Auth::id())
                ->orderByDesc('last_activity')
            : BrowserSession::query()->whereRaw('1 = 0');

        return $table
            ->query(fn () => $query)
            ->columns([
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(80)
                    ->tooltip(fn ($state): string => (string) $state)
                    ->searchable(),
                TextColumn::make('last_activity')
                    ->label('Terakhir Aktif')
                    ->formatStateUsing(fn ($state): string => Carbon::createFromTimestamp((int) $state)->diffForHumans()),
                TextColumn::make('status_label')
                    ->label('Status'),
            ])
            ->paginated(false);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('logoutOthers')
                ->label('Logout Other Devices')
                ->icon(Heroicon::OutlinedArrowRightOnRectangle)
                ->requiresConfirmation()
                ->schema([
                    \Filament\Forms\Components\TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $user = Auth::user();

                    if (! $user || ! Hash::check((string) ($data['password'] ?? ''), (string) $user->getAuthPassword())) {
                        Notification::make()
                            ->title(__('The provided password is incorrect.'))
                            ->danger()
                            ->send();

                        return;
                    }

                    if (config('session.driver') === 'database') {
                        DB::connection(config('session.connection'))
                            ->table(config('session.table', 'sessions'))
                            ->where('user_id', $user->getAuthIdentifier())
                            ->where('id', '!=', session()->getId())
                            ->delete();
                    } else {
                        Auth::logoutOtherDevices((string) $data['password']);
                    }

                    Notification::make()
                        ->title('Logged out from other devices')
                        ->success()
                        ->send();

                    $this->dispatch('$refresh');
                }),
        ];
    }
}
