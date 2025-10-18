@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="Pagination Navigation" style="display:flex; gap:8px; justify-content:center; align-items:center;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="opacity:0.5; color:rgb(159, 159, 159)">Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="opacity:0.9; color:rgb(159, 159, 159)" rel="prev">Prev</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span style="opacity:0.7;">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="font-weight:600;opacity:0.9; color:rgb(159, 159, 159)">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="opacity:0.5; color:rgb(159, 159, 159)">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"  style="opacity:0.9; color:rgb(159, 159, 159)">Next</a>
        @else
            <span style="opacity:0.5; color:rgb(159, 159, 159)">Next</span>
        @endif
    </nav>
@endif
