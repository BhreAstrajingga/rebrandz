<div class="mb-4">
    <div class="w-full max-w-[20ch] aspect-square border border-gray-300 bg-gray-200 p-4 rounded-lg flex items-center justify-center dark:border-gray-600 dark:bg-gray-700">
        <span class="text-5xl font-bold text-gray-800 dark:text-gray-100">
            {{ isset($get) ? $get('symbol') : ($symbol ?? '') }}
        </span>
    </div>
</div>
