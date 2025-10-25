<?php

namespace App\Livewire\Auth;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),
            Forms\Components\Checkbox::make('remember')
                ->label('Remember me'),
        ])->statePath('.');
    }

    public function submit(): void
    {
        $key = 'login:'.Str::lower($this->email).':'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            Notification::make()->title('Too many attempts')->danger()->send();

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key, 60);
            Notification::make()->title('Invalid credentials')->danger()->send();

            return;
        }

        RateLimiter::clear($key);

        $user = Auth::user();
        $destination = match ($user->user_type) {
            'system' => '/admin',
            'customer' => '/user',
            default => '/',
        };

        $this->redirect($destination, navigate: true);
    }
}
