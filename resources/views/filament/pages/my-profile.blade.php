<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl !border-0 !shadow-lg"
            style="background: linear-gradient(135deg, rgb(248, 250, 252) 0%, rgb(226, 232, 240) 50%, rgb(203, 213, 225) 100%); position: relative;">
            <div class="relative p-4 sm:p-6 md:p-8 text-gray-600 dark:text-gray-300">
                <div class="flex justify-between items-start mb-4 sm:mb-6">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="mr-3 sm:mr-4 shadow-md flex-shrink-0 bg-slate-500 dark:bg-slate-400 rounded-full flex items-center justify-center text-white font-semibold text-4xl"
                            style="width:72px; height:72px">
                            SU
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base sm:text-lg font-semibold truncate text-gray-800 dark:text-gray-100">
                                {{ $userData->email }}
                            </div>
                            <div class="mt-1 flex flex-wrap gap-1 sm:gap-2">
                                <x-filament::badge size="sm" class="text-xs" color="primary">
                                    Normal User
                                </x-filament::badge>
                                <x-filament::badge size="sm" class="text-xs" color="info">
                                    ID: 16258
                                </x-filament::badge>
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center shadow-md flex-shrink-0 ml-2 bg-slate-500 dark:bg-slate-500">

                    </div>
                </div>
                <div class="mb-4 sm:mb-6">
                    <div class="text-xs sm:text-sm mb-1 sm:mb-2 text-gray-500 dark:text-gray-400">Credit balance</div>
                    <div
                        class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-wide text-gray-900 dark:text-gray-100">
                        $9.40</div>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end">
                    <div class="grid grid-cols-3 gap-2 sm:flex sm:space-x-6 lg:space-x-8 mb-3 sm:mb-0">
                        <div class="text-center sm:text-left">
                            <div class="text-xs text-gray-400 dark:text-gray-500">Active Subscriptions</div>
                            <div class="text-xs sm:text-sm font-medium truncate text-gray-600 dark:text-gray-300">
                                -
                            </div>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-xs text-gray-400 dark:text-gray-500">Expired subscriptions</div>
                            <div class="text-xs sm:text-sm font-medium truncate text-gray-600 dark:text-gray-300">
                                1
                            </div>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-xs text-gray-400 dark:text-gray-500">Next Due</div>
                            <div class="text-xs sm:text-sm font-medium truncate text-gray-600 dark:text-gray-300">
                                default
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-4">Subscriptions</h2>
            @livewire(\app\Filament\Widgets\SubscriptionListWidget::class)
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-4">Invoices</h2>
            @livewire(\app\Filament\Widgets\InvoiceListWidget::class)
        </div>

    </div>
</x-filament-panels::page>
