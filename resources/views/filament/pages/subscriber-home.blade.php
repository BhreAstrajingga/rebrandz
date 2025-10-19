<x-filament::page>
    <div class="space-y-3">
        <h2 class="text-xl font-semibold">Subscriber Home</h2>
        <p class="text-sm text-gray-600">Welcome. This is your main dashboard. Manage your subscriptions, payments, and tenant ownership here.</p>

        @if (filament()->auth()->user()?->tenant_id)
            <div>
                <a href="{{ \App\Filament\Pages\TenantHome::getUrl(tenant: filament()->getUserDefaultTenant(), panel: 'tenant') }}" class="text-primary-600 hover:underline">
                    Go to My Tenant
                </a>
            </div>
        @else
            <div class="flex items-center gap-3">
                <a href="{{ url('/tenant/register-tenant') }}" class="px-3 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                    Create Tenant
                </a>
                <span class="text-sm text-gray-600">You can create a tenant anytime.</span>
            </div>
        @endif
    </div>
</x-filament::page>

