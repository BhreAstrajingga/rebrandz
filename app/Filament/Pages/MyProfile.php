<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Page
{
    //fungsi-fungsi halaman profil pengguna
    protected string $view = 'filament.pages.my-profile';
    protected static ?string $slug = 'my-profile';

    public ?User $userData = null;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }


    // data untuk ditampilkan di halaman profil pengguna
    public function mount(): void
    {
        $this->userData = Auth::user();
    }
}
