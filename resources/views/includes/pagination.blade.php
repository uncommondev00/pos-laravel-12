@if ($paginator->hasPages())
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <div class="dataTables_paginate paging_simple_numbers">
            <ul class="pagination" style="margin: 2px 0; white-space: nowrap;">
                {{-- Previous Page Link --}}
                <li class="paginate_button page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" wire:click.prevent="previousPage" href="#" tabindex="-1">Previous</a>
                </li>

                {{-- Pagination Elements --}}
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    @if ($i == $paginator->currentPage())
                    <li class="paginate_button page-item active" wire:key="page-{{ $i }}">
                        <a class="page-link" href="#">{{ $i }}</a>
                    </li>
                    @elseif ($i == 1 || $i == $paginator->lastPage() || abs($paginator->currentPage() - $i) <= 2)
                        <li class="paginate_button page-item" wire:key="page-{{ $i }}">
                        <a class="page-link" wire:click.prevent="gotoPage({{ $i }})" href="#">{{ $i }}</a>
                        </li>
                        @elseif (abs($paginator->currentPage() - $i) == 3)
                        <li class="paginate_button page-item disabled" wire:key="dots-{{ $i }}">
                            <a class="page-link" href="#">...</a>
                        </li>
                        @endif
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="paginate_button page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" wire:click.prevent="nextPage" href="#">Next</a>
                        </li>
            </ul>
        </div>
    </div>
</div>
@endif
