<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Assign Member ke Cabang</x-slot>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Cabang</label>
                    <x-filament::select wire:model="selectedBranchId">
                        <option value="">Pilih Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </x-filament::select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Member</label>
                    <x-filament::select wire:model="selectedMemberId">
                        <option value="">Pilih Member</option>
                        @foreach ($tenantMembers as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
                        @endforeach
                    </x-filament::select>
                </div>
                <div class="flex items-end">
                    <x-filament::button size="sm" color="primary" wire:click="assignMember">Assign</x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Daftar Cabang & Anggota</x-slot>
            <div class="space-y-4">
                @foreach ($branches as $branch)
                    <div class="border rounded p-4">
                        <div class="font-semibold">{{ $branch->name }} <span class="text-sm text-gray-500">({{ $branch->code }})</span></div>
                        <div class="mt-2">
                            @if ($branch->members->isEmpty())
                                <div class="text-gray-500 text-sm">Belum ada anggota di cabang ini.</div>
                            @else
                                <ul class="list-disc pl-5">
                                    @foreach ($branch->members as $m)
                                        <li class="flex items-center justify-between">
                                            <div>{{ $m->name }} <span class="text-gray-500">({{ $m->email }})</span></div>
                                            <x-filament::button size="xs" color="danger" wire:click="removeMember({{ $branch->id }}, {{ $m->id }})">Hapus</x-filament::button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

