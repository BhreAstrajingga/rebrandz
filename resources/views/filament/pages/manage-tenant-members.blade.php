<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Tambah Member</x-slot>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-filament::input.wrapper>
                        <x-filament::input type="email" wire:model.live="email" placeholder="email@example.com" />
                    </x-filament::input.wrapper>
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm text-gray-600 mb-2">Assign ke cabang (opsional jika lebih dari 1)</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($branches as $branch)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" wire:model.live="selectedBranches" value="{{ $branch->id }}" />
                                <span>{{ $branch->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <x-filament::button size="sm" color="primary" wire:click="addMember">Tambah</x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Daftar Member</x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Email</th>
                            <th class="py-2 pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $m)
                            <tr class="border-t">
                                <td class="py-2 pr-4">{{ $m->name }}</td>
                                <td class="py-2 pr-4">{{ $m->email }}</td>
                                <td class="py-2 pr-4">
                                    <x-filament::button size="xs" color="danger" wire:click="removeMember({{ $m->id }})">Hapus</x-filament::button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-gray-500">Belum ada member.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

