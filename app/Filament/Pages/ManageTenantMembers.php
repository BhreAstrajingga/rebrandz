<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ManageTenantMembers extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Staff';

    protected static ?string $slug = 'tenant/staff';

    protected string $view = 'filament.pages.manage-tenant-members';

    public ?int $tenantId = null;

    public Collection $members;

    public Collection $branches;

    public string $email = '';

    public array $selectedBranches = [];

    public function mount(): void
    {
        $user = Filament::auth()->user();
        $this->tenantId = $user?->tenant_id ? (int) $user->tenant_id : null;

        if (! $this->tenantId) {
            $this->members = collect();
            $this->branches = collect();

            return;
        }

        $tenant = Tenant::query()->with(['members', 'branches'])->find($this->tenantId);
        // Hanya Tenant Owner yang boleh akses halaman ini
        if (! $tenant || (int) $tenant->owner_id !== (int) ($user?->id)) {
            abort(403);
        }
        $this->members = $tenant?->members ?? collect();
        $this->branches = $tenant?->branches ?? collect();
    }

    public function addMember(): void
    {
        if (! $this->tenantId) {
            return;
        }

        $email = trim($this->email);
        if ($email === '') {
            Notification::make()->title('Email wajib diisi')->danger()->send();

            return;
        }

        $tenant = Tenant::query()->with('branches')->findOrFail($this->tenantId);

        $user = User::query()->firstOrCreate(
            ['email' => strtolower($email)],
            [
                'name' => substr($email, 0, strpos($email, '@')) ?: $email,
                'password' => bcrypt(str()->random(12)),
                'user_type' => 'branch_member',
            ],
        );

        // Ensure tenant-level membership
        $tenant->members()->syncWithoutDetaching([$user->id => ['status' => 'active', 'assigned_by' => Filament::auth()->id()]]);

        // Assign branches
        $branches = $tenant->branches;
        if ($branches->count() <= 1) {
            $default = $branches->first();
            if ($default) {
                $default->members()->syncWithoutDetaching([$user->id => ['status' => 'active']]);
            }
        } else {
            $ids = array_map('intval', $this->selectedBranches);
            $ids = $branches->whereIn('id', $ids)->pluck('id')->all();
            foreach ($ids as $bid) {
                Branch::query()->where('id', $bid)->first()?->members()->syncWithoutDetaching([$user->id => ['status' => 'active']]);
            }
        }

        $this->resetForm();
        $this->refreshData();
        Notification::make()->title('Member ditambahkan')->success()->send();
    }

    public function removeMember(int $userId): void
    {
        if (! $this->tenantId) {
            return;
        }

        $tenant = Tenant::query()->with('branches')->findOrFail($this->tenantId);

        // Detach from tenant and its branches
        $tenant->members()->detach($userId);
        foreach ($tenant->branches as $branch) {
            $branch->members()->detach($userId);
        }

        $this->refreshData();
        Notification::make()->title('Member dihapus')->success()->send();
    }

    protected function resetForm(): void
    {
        $this->email = '';
        $this->selectedBranches = [];
    }

    protected function refreshData(): void
    {
        $tenant = Tenant::query()->with(['members', 'branches'])->find($this->tenantId);
        $this->members = $tenant?->members ?? collect();
        $this->branches = $tenant?->branches ?? collect();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();
        if (Filament::getCurrentPanel()?->getId() !== 'user' || ! $user?->tenant_id) {
            return false;
        }

        $tenant = Tenant::query()->select(['id', 'owner_id'])->find((int) $user->tenant_id);

        return (bool) ($tenant && (int) $tenant->owner_id === (int) $user->id);
    }
}
