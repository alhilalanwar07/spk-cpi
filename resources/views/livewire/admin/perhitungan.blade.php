<div>
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
                    <li class="breadcrumb-item active" aria-current="page">Perhitungan</li>
                </ol>
            </nav>
            <h2 class="h4">Perhitungan</h2>
            <p class="mb-0"></p>
        </div>
    </div>

    @if(!$hasilHitungCPI)
    <div class="card card-body border-0 shadow table-wrapper table-responsive mb-4">
        <table class="table table-bordered table-striped mb-0">
            <thead class="text-center justify-content-center">
                <tr>
                    <th rowspan="2" class="border-gray-200 text-center align-content-center" style="width: 5%">#</th>
                    <th rowspan="2" class="border-gray-200 text-center align-content-center">Alternatif</th>
                    <th colspan="{{ count($kriteria)}}" class="border-gray-200">Kriteria</th>
                </tr>
                <tr>
                    @foreach ($kriteria as $item)
                    <th class="border-gray-200">{{ $item->nama_kriteria }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatif as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nama_alternatif }}</td>
                    @foreach ($kriteria as $k)
                    <td>
                        <select class="form-control" wire:model.live="subkriteria.{{ $key }}.{{ $k->id }}">
                            <option value="">Pilih Subkriteria</option>
                            @foreach ($k->subkriteria as $sub)
                            <option value="{{ $sub->id }}" {{ isset($subkriteria[$key][$k->id]) && $subkriteria[$key][$k->id] == $sub->id ? 'selected' : '' }}>
                                ({{ $sub->bobot }}) {{ $sub->nama_subkriteria }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-primary" wire:click="hitung">Hitung</button>
        </div>
    </div>
    @endif
    @if($hasilHitungCPI)
    <div class="mt-0">
        <!-- Tabel 1: Nilai Kriteria Tiap Alternatif -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tabel 1. Nilai Kriteria Tiap Alternatif</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alternatif</th>
                            @foreach($kriteria as $k)
                            <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $a)
                        <tr>
                            <td>{{ $a->nama_alternatif }}</td>
                            @foreach($kriteria as $k)
                            <td>{{ $nilai[$a->id][$k->id] ?? 0 }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 2: Nilai MIN dan MAX -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tabel 2. Nilai MIN dan MAX</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nilai</th>
                            @foreach($kriteria as $k)
                            <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MIN</td>
                            @foreach($kriteria as $k)
                            <td>{{ $nilaiMin[$k->id] ?? 0 }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>MAX</td>
                            @foreach($kriteria as $k)
                            <td>{{ $nilaiMax[$k->id] ?? 0 }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 3: Normalisasi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tabel 3. Normalisasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alternatif</th>
                            @foreach($kriteria as $k)
                            <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $a)
                        <tr>
                            <td>{{ $a->nama_alternatif }}</td>
                            @foreach($kriteria as $k)
                            @php
                            $nilaiAwal = $nilai[$a->id][$k->id] ?? 0;
                            $min = $nilaiMin[$k->id];
                            $normalisasi = ($nilaiAwal / $min)*100;
                            @endphp
                            <td>{{ number_format($normalisasi, 2, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 4: Bobot x Normalisasi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tabel 4. Bobot x Normalisasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alternatif</th>
                            @foreach($kriteria as $k)
                            <th>{{ $k->nama_kriteria }} ({{ $k->bobot }})</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $a)
                        <tr>
                            <td>{{ $a->nama_alternatif }}</td>
                            @php $total = 0; @endphp
                            @foreach($kriteria as $k)
                            @php
                            $nilaiAwal = $nilai[$a->id][$k->id] ?? 0;
                            $min = $nilaiMin[$k->id];
                            $normalisasi = ($nilaiAwal / $min)*100;
                            $bobotNormalisasi = $k->bobot * $normalisasi;
                            $total += $bobotNormalisasi;
                            @endphp
                            <td>{{ number_format($bobotNormalisasi, 2, ',', '.') }}</td>
                            @endforeach
                            <td>{{ number_format($total, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 5: Ranking -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tabel 5. Ranking</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Alternatif</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $ranking = collect($alternatif)->map(function($a) use ($kriteria, $nilai, $nilaiMin, $nilaiMax) {
                        $total = 0;
                        foreach($kriteria as $k) {
                        $nilaiAwal = $nilai[$a->id][$k->id] ?? 0;
                        $min = $nilaiMin[$k->id];
                        $normalisasi = ($nilaiAwal / $min)*100;
                        $total += $normalisasi * $k->bobot;
                        }
                        return [
                        'alternatif' => $a->nama_alternatif,
                        'alternatif_id' => $a->id,
                        'nilai' => $total
                        ];
                        })->sortByDesc('nilai')->values();
                        @endphp

                        @foreach($ranking as $index => $rank)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $rank['alternatif'] }}</td>
                            <td>{{ number_format($rank['nilai'], 2, ',', '.') }}</td>
                            <td class="text-center">
                                <input type="radio"
                                    wire:model="selectedAlternative"
                                    value="{{ $rank['alternatif_id'] }}"
                                    wire:click="selectAlternative('{{ $rank['alternatif_id'] }}')"
                                    style="transform: scale(1.5);">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-success text-white" wire:click="simpanPilihan">Simpan Pilihan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal simpan pilihan -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Simpan Pilihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-primary" wire:click="simpanP">Ya</button>
                </div>
            </div>
        </div>
    </div>
    
    @endif
    <!-- End of Modal Content -->
    @livewire('alert')
</div>