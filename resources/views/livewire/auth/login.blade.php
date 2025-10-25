<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-black/20">
    <div class="w-full max-w-md rounded-xl bg-white/90 dark:bg-black/50 p-6 shadow">
        <h1 class="text-xl font-semibold mb-4">Sign in</h1>
        <form wire:submit.prevent="submit" class="space-y-4">
            {{ $this->form }}

            <div class="flex items-center justify-end">
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Login</button>
            </div>
        </form>
    </div>
</div>
