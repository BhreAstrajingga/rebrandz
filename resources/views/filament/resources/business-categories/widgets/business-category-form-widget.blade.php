<div>
    <x-filament::section collapsible>
        <x-slot name="heading">New Category</x-slot>
        <form wire:submit="save">
            <div>
                {{ $this->getSchema('form') }}
            </div>
            <div class="mt-6 flex justify-end gap-2">
                @if ($categoryId)
                    <x-filament::button
                        color="danger"
                        wire:click="delete"
                        type="button"
                        size="sm"
                    >
                        Delete
                    </x-filament::button>
                @endif

                <x-filament::button type="submit" size="sm">
                    {{ $categoryId ? 'Update category' : 'Submit category' }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</div>
