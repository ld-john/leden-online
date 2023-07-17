<div class="w-50">
    @if($paginator->hasPages())
        <nav>
            <ul class="pagination justify-content-end">
                <li class="page-item" >
                    <a class="page-link" wire:click="previousPage('factoryOptionsPage')" wire:loading.attr="disabled" rel="prev">Prev</a>
                </li>
                @foreach($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <a class="page-link">{{ $element }}</a>
                        </li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active">
                                    <a class="page-link" wire:click="gotoPage({{ $page }}, 'factoryOptionsPage')">{{ $page }}</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" wire:click="gotoPage({{ $page }}, 'factoryOptionsPage')">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                <li class="page-item">
                    <a class="page-link" wire:click="nextPage('factoryOptionsPage')" wire:loading.attr="disabled" rel="next">Next</a>
                </li>
            </ul>
        </nav>
    @endif
</div>