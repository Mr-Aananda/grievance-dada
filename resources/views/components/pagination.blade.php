<nav aria-label="Pagination" class="float-end my-3">
    @if ($items->lastPage() > 1)
        <ul class="pagination">
            <!-- Previous Button -->
            <li class="page-item{{ $items->currentPage() === 1 ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $items->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $items->currentPage() === 1 ? 'true' : 'false' }}">Previous</a>
            </li>

            <!-- First Page and Ellipsis -->
            @if ($items->currentPage() > 3)
                <li class="page-item">
                    <a class="page-link" href="{{ $items->url(1) }}">1</a>
                </li>
                @if ($items->currentPage() > 4)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            <!-- Middle Pages (around current page) -->
            @foreach (range(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $page)
                <li class="page-item{{ $page === $items->currentPage() ? ' active' : '' }}">
                    <a class="page-link" href="{{ $items->url($page) }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Ellipsis and Last Page -->
            @if ($items->currentPage() < $items->lastPage() - 2)
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            @endif
            @if ($items->currentPage() < $items->lastPage() - 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
                </li>
            @endif

            <!-- Next Button -->
            <li class="page-item{{ !$items->hasMorePages() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $items->nextPageUrl() }}" aria-disabled="{{ !$items->hasMorePages() ? 'true' : 'false' }}">Next</a>
            </li>
        </ul>
    @endif
</nav>
