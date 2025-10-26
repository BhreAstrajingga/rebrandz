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
use Illuminate\Support\Facades\Gate;

class ManageBranches extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Branches';

    protected static ?string $slug = 'tenant/branches';

    protected string $view = 'filament.pages.manage-branches';

    public ?int $tenantId = null;

    public Collection $branches;

    public Collection $tenantMembers;

    public ?int $selectedBranchId = null;

    public ?int $selectedMemberId = null;

    public function mount(): void
    {
        $user = Filament::auth()->user();
        $this->tenantId = $user?->tenant_id ? (int) $user->tenant_id : null;

        if (! $this->tenantId || ! Gate::allows('access-tenant', $this->tenantId)) {
            abort(403);
        }

        $this->refreshData();
    }

    public function assignMember(): void
    {
        if (! $this->tenantId || ! Gate::allows('access-tenant', $this->tenantId)) {
            abort(403);
        }

        $branchId = (int) ($this->selectedBranchId ?? 0);
        $userId = (int) ($this->selectedMemberId ?? 0);

        if ($branchId === 0 || $userId === 0) {
            Notification::make()->title('Pilih cabang dan member').->danger()->send();
            return;
        }

        $branch = Branch::query()->where('tenant_id', $this->tenantId)->find($branchId);
        $user = User::query()->find($userId);

        if (! $branch || ! $user) {
            Notification::make()->title('Data tidak valid')->danger()->send();
            return;
        }

        $branch->members()->syncWithoutDetaching([$userId => ['status' => 'active']]);
        $this->selectedMemberId = null;
        $this->refreshData();
        Notification::make()->title('Member ditambahkan ke cabang')->success()->send();
    }

    public function removeMember(int $branchId, int $userId): void
    {
        if (! $this->tenantId || ! Gate::allows('access-tenant', $this->tenantId)) {
            abort(403);
        }

        $branch = Branch::query()->where('tenant_id', $this->tenantId)->find($branchId);
        if (! $branch) {
            abort(404);
        }

        $branch->members()->detach($userId);
        $this->refreshData();
        Notification::make()->title('Member dihapus dari cabang')->success()->send();
    }

    protected function refreshData(): void
    {
        $tenant = Tenant::query()->with(['branches.members', 'members'])->find($this->tenantId);
        $this->branches = $tenant?->branches ?? collect();
        $this->tenantMembers = $tenant?->members ?? collect();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();
        if (Filament::getCurrentPanel()?->getId() !== 'user' || ! $user?->tenant_id) {
            return false;
        }

        return Gate::allows('access-tenant', (int) $user->tenant_id);
    }
}

