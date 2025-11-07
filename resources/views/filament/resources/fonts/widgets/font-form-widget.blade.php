<div>
    <x-filament::section collapsible>
        <x-slot name="heading">
			{{ $fontId ? ($data['name'] ?? 'Edit Font') : 'New Font' }}
		</x-slot>
        <form wire:submit="save">
            <div>
                {{ $this->getSchema('form') }}
            </div>
            @php($source = $data['source'] ?? null)
            @php($name = $data['name'] ?? null)
            @php($isUrl = is_string($source) && \Illuminate\Support\Str::startsWith($source, ['http://', 'https://']))
            @php(
                $family = $name ?: (function($src) {
                    if (! is_string($src)) { return null; }
                    if (preg_match('/[?&]family=([^:&]+)/', $src, $m) === 1) {
                        $candidate = str_replace('+', ' ', $m[1] ?? '');
                        $candidate = preg_replace('/:.*/', '', $candidate);
                        return $candidate ?: null;
                    }
                    return null;
                })($source)
            )
            @if ($source)
                <div class="mt-6">
                    <x-filament::section>
                        <x-slot name="heading">Preview</x-slot>
                        @if ($isUrl)
                            <link rel="stylesheet" href="{{ $source }}">
                        @else
                            <style>{!! $source !!}</style>
                        @endif
                        <div class="space-y-3">
                            <div class="p-4 border rounded">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Sample</div>
                                <div class="mt-2 text-2xl" @if(!empty($family)) style="font-family: '{{ $family }}', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, \"Noto Color Emoji\", \"Apple Color Emoji\", \"Segoe UI Emoji\", sans-serif;" @endif>
                                    The quick brown fox jumps over the lazy dog 1234567890
                                </div>
                            </div>
                        </div>
                    </x-filament::section>
                </div>
            @endif
            <div class="mt-6 flex justify-end gap-2">
                @if ($fontId)
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
                    {{ $fontId ? 'Submit Changes' : 'Submit Data' }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</div>
