@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-nav">
        <div class="pagination-summary">
            <p>
                Rodoma
                <strong>{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? $paginator->count() }}</strong>
                iš
                <strong>{{ $paginator->total() }}</strong>
                kampanijų
            </p>
        </div>

        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="pagination-link is-arrow is-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    &lsaquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link is-arrow" aria-label="{{ __('pagination.previous') }}">
                    &lsaquo;
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-separator" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-link is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-link" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link is-arrow" aria-label="{{ __('pagination.next') }}">
                    &rsaquo;
                </a>
            @else
                <span class="pagination-link is-arrow is-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    &rsaquo;
                </span>
            @endif
        </div>
    </nav>
@endif
