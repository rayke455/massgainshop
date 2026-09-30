<nav id="pagination">
    @if ($paginator->hasPages())
        <ul class="pagination wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <li class="disabled page-item">
                    <a class="page-link" href="#"><i class="fal fa-long-arrow-left"></i></a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="fal fa-long-arrow-left"></i></a>
                </li>
            @endif

            <!-- First Page -->
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
            </li>

            <!-- Ellipses Before -->
            @if ($paginator->currentPage() > 4)
                <li class="disabled page-item">
                    <a class="page-link" href="#">...</a>
                </li>
            @endif

            <!-- Page Links Around Current Page -->
            @foreach (range(max(2, $paginator->currentPage() - 3), min($paginator->lastPage() - 1, $paginator->currentPage() + 3)) as $page)
                <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Ellipses After -->
            @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                <li class="disabled page-item">
                    <a class="page-link" href="#">...</a>
                </li>
            @endif

            <!-- Last Page -->
            @if ($paginator->lastPage() > 1)
                <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            <!-- Next Page Link -->
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="fal fa-long-arrow-right"></i></a>
                </li>
            @else
                <li class="disabled page-item">
                    <a class="page-link" href="#"><i class="fal fa-long-arrow-right"></i></a>
                </li>
            @endif
        </ul>
    @endif
</nav>
