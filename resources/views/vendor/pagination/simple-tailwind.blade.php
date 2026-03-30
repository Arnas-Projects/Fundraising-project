@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav">
        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="pagination-link is-arrow is-disabled" aria-disabled="true">
                    {{ __('pagination.previous') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link is-arrow">
                    {{ __('pagination.previous') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link is-arrow">
                    {{ __('pagination.next') }}
                </a>
            @else
                <span class="pagination-link is-arrow is-disabled" aria-disabled="true">
                    {{ __('pagination.next') }}
                </span>
            @endif
        </div>
    </nav>
@endif
