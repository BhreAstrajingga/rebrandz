{{-- resources\views\filament\resources\user-subscriptions\widgets\user-subscription-form-widget.blade.php --}}
<div>
    <x-filament::section collapsible>
        <x-slot name="heading">New Subscription</x-slot>
        <form wire:submit="save">
            <div>
                {{ $this->getSchema('form') }}
            </div>
            <div class="mt-6 flex justify-end gap-2">
                @if ($subscriptionId)
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
                    {{ $subscriptionId ? 'Update subscription' : 'Submit subscription' }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</div>
