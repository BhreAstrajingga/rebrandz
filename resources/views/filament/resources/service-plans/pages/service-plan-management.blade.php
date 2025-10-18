{{-- resources\views\filament\resources\service-plans\pages\service-plan-management.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
		<div class="grid grid-cols-1 md:grid-cols-6 gap-6">
			<div class="md:col-span-2">
                @livewire(\App\Filament\Resources\ServicePlans\Widgets\ServicePlanListWidget::class)
			</div>
			<div class="md:col-span-4">
				@livewire(\App\Filament\Resources\ServicePlans\Widgets\ServicePlanFormWidget::class)
			</div>
		</div>
	</div>
</x-filament-panels::page>
