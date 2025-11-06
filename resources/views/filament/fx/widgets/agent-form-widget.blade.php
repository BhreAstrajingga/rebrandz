<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
			{{ $agentId ? ($data['agent_code'] .' | '.$data['name'] ?? 'Agent Name') : 'New Agent' }}
		</x-slot>
        <form wire:submit="save">
            <div>
                {{ $this->getSchema('form') }}
            </div>
            <div class="mt-6 flex justify-end gap-2">
                @if ($agentId)
                    <x-filament::button color="danger" wire:click="delete" type="button" size="sm">
                        Delete
                    </x-filament::button>
                @endif

                <x-filament::button type="submit" size="sm">
                    {{ $agentId ? 'Update Data' : 'Submit Data' }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
