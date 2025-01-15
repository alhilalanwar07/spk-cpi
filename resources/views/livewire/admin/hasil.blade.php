<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hasil</li>
                </ol>
            </nav>
            <h2 class="h4">Hasil</h2>
            <p class="mb-0"></p>
        </div>
    </div>
    <div class="table-settings mb-4">
        <div class="row align-items-center justify-content-between">
            <div class="col col-md-6 col-lg-3 col-xl-4">
                <div class="input-group me-2 me-lg-3 fmxw-400">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Search...">
                </div>
            </div>
        </div>
    </div>
    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="border-gray-200" style="width: 5%">#</th>
                    <th class="border-gray-200">Alternatif</th>
                    <th class="border-gray-200">Nilai CPI</th>
                    <th class="border-gray-200">Rank</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasils as $hasil)
                <tr>
                    <td>
                        <a href="#" class="fw-bold">
                            {{ ($loop->index) + (($hasils->currentPage() - 1) * $hasils->perPage()) + 1 }}
                        </a>
                    </td>
                    <td>
                        <span class="fw-normal">{{ $hasil->alternatif->nama_alternatif }}</span>
                    </td>
                    <td>
                        <span class="fw-normal">{{ number_format($hasil->nilai_cpi, 2, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="fw-normal">{{ $hasil->rank }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">{{ $hasils->links() }}</div>
    </div>
</div>