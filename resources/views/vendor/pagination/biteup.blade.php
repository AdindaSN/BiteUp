@if ($paginator->hasPages())
    <nav class="bu-pager" role="navigation" aria-label="Pagination">
        <div class="bu-pager-info">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} data
        </div>

        <ul class="bu-pager-list">
            {{-- Tombol sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&lsaquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="dots"><span>…</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol berikutnya --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="disabled"><span>&rsaquo;</span></li>
            @endif
        </ul>
    </nav>

    <style>
        .bu-pager { display:flex; justify-content:space-between; align-items:center;
                    flex-wrap:wrap; gap:12px; margin-top:18px; }
        .bu-pager-info { font-size:13px; color:#6b7280; }
        .bu-pager-list { display:flex; gap:6px; list-style:none; margin:0; padding:0; }
        .bu-pager-list li a,
        .bu-pager-list li span { display:flex; align-items:center; justify-content:center;
                                 min-width:36px; height:36px; padding:0 10px; font-size:14px;
                                 border:1px solid #d1d5db; border-radius:8px; background:#fff;
                                 color:#1b5e3f; text-decoration:none; }
        .bu-pager-list li a:hover { background:#e8f3ec; border-color:#2e7d32; }
        .bu-pager-list li.active span { background:#2e7d32; border-color:#2e7d32;
                                        color:#fff; font-weight:600; }
        .bu-pager-list li.disabled span { background:#f3f4f6; color:#9ca3af; cursor:not-allowed; }
        .bu-pager-list li.dots span { border:0; background:transparent; color:#6b7280; min-width:24px; }
    </style>
@endif