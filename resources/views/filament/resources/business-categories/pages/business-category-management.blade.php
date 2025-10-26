<x-filament-panels::page>
    <div class="space-y-6">
		<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
			<div class="md:col-span-2 gap-6">
                @livewire(\app\Filament\Resources\BusinessCategories\Widgets\BusinessCategoryListWidget::class)
			</div>
			<div class="md:col-span-3">
                @livewire(\app\Filament\Resources\BusinessCategories\Widgets\BusinessCategoryFormWidget::class)
			</div>
		</div>
	</div>
</x-filament-panels::page>
