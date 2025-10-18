<x-filament::page>
    <div>
        <h2 class="text-xl font-semibold">Welcome to your tenant dashboard</h2>
        <p class="text-sm text-gray-600">Tenant: {{ filament()->getTenant()?->name ?? '-' }}</p>
    </div>
</x-filament::page>

