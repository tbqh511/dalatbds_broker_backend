@if ($paginator->hasPages())
    <nav class="ltbs-pager" role="navigation" aria-label="Phân trang">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="pg is-disabled" aria-disabled="true">‹</span>
        @else
            <a class="pg" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg is-dots">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pg" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a class="pg" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
        @else
            <span class="pg is-disabled" aria-disabled="true">›</span>
        @endif
    </nav>
@endif
