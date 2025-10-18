{{-- resources\views\filament\resources\service-plans\widgets\service-plan-form-widget.blade.php --}}
<div>
    <x-filament::section collapsible>
        <x-slot name="heading">New Service</x-slot>
        <form wire:submit="save">
            <div>
                {{ $this->getSchema('form') }}
            </div>
            <div class="mt-6 flex justify-end gap-2">
                @if ($servicePlanId)
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
                    {{ $servicePlanId ? 'Update Service' : 'Submit Service' }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</div>
