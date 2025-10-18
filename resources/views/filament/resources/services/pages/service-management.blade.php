{{-- resources\views\filament\resources\services\pages\service-management.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
		<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
			<div class="md:col-span-2 gap-6">
                @livewire(\App\Filament\Resources\Services\Widgets\ServiceListWidget::class)
			</div>
			<div class="md:col-span-3">
				@livewire(\App\Filament\Resources\Services\Widgets\ServiceFormWidget::class)
			</div>
		</div>
	</div>
</x-filament-panels::page>
