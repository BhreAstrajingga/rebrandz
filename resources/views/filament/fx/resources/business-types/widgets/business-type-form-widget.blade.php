<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
			{{ $businessTypeId ? ($data['name'] ?? 'Edit Business Type') : 'New Business Type' }}
		</x-slot>
        <form wire:submit="save">
            <div>
                {{ $this->getSchema('form') }}
            </div>
            <div class="mt-6 flex justify-end gap-2">
                @if ($businessTypeId)
                    <x-filament::button color="danger" wire:click="delete" type="button" size="sm">
                        Delete
                    </x-filament::button>
                @endif

                <x-filament::button type="submit" size="sm">
                    {{ $businessTypeId ? 'Save changes' : 'Submit Data' }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
