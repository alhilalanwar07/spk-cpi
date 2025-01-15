<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Livewire\Component;

class Perhitungan extends Component
{
    public $kriteria;
    public $alternatif;
    public $subkriteria = []; // Tambahkan properti ini
    public $nilai = [];
    public $nilaiMin = [];  // Add this
    public $nilaiMax = [];  // Add this

    public $hasilHitungCPI = false;

    public $selectedAlternative = null;
    public $kode_unik;

    public function mount()
    {
        $this->kriteria = \App\Models\Kriteria::with('subkriteria')->get();
        $this->alternatif = \App\Models\Alternatif::all();

        // Initialize subkriteria array
        foreach ($this->alternatif as $aIndex => $alt) {
            foreach ($this->kriteria as $k) {
                $found = \App\Models\AlternatifSubkriteria::join('subkriterias', 'alternatif_subkriterias.subkriteria_id', '=', 'subkriterias.id')
                    ->where('alternatif_subkriterias.alternatif_id', $alt->id)
                    ->where('subkriterias.kriteria_id', $k->id)
                    ->select('alternatif_subkriterias.*')
                    ->first();

                $this->subkriteria[$aIndex][$k->id] = $found ? $found->subkriteria_id : '';
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.perhitungan', [
            'kriteria' => $this->kriteria,
            'alternatif' => $this->alternatif,
        ]);
    }

    public function simpanData()
    {
        // Loop tiap alternatif dan kriteria
        foreach ($this->subkriteria as $alternatifIndex => $kriteriaItems) {
            $alternatifId = $this->alternatif[$alternatifIndex]->id;
            foreach ($kriteriaItems as $kriteriaId => $subkriteriaId) {
                if (!empty($subkriteriaId)) {
                    // Simpan data sesuai struktur tabel Anda, misalnya:
                    \App\Models\AlternatifKriteria::updateOrCreate(
                        ['alternatif_id' => $alternatifId, 'kriteria_id' => $kriteriaId],
                        ['subkriteria_id' => $subkriteriaId]
                    );
                }
            }
        }

        // Berikan notifikasi atau feedback setelah berhasil menyimpan
        $this->dispatch('updateAlertToast', [
            'title'     => 'Data tersimpan',
            'text'      => 'Pilihan subkriteria telah disimpan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);
    }

    public function updatedSubkriteria($value, $propertyName)
    {
        try {
            $indexes = explode('.', $propertyName);
            $alternatifIndex = $indexes[0];
            $kriteriaId = $indexes[1];

            $alternatifId = $this->alternatif[$alternatifIndex]->id;

            if (!empty($value)) {
                $subkriteria = \App\Models\Subkriteria::find($value);

                // Delete existing entries first
                \App\Models\AlternatifSubkriteria::join('subkriterias', 'alternatif_subkriterias.subkriteria_id', '=', 'subkriterias.id')
                    ->where('alternatif_subkriterias.alternatif_id', $alternatifId)
                    ->where('subkriterias.kriteria_id', $kriteriaId)
                    ->delete();

                // Create new entry
                \App\Models\AlternatifSubkriteria::create([
                    'alternatif_id' => $alternatifId,
                    'subkriteria_id' => $value,
                    'nilai' => $subkriteria->bobot
                ]);

                $this->dispatch('updateAlertToast', [
                    'title' => 'Berhasil',
                    'text' => 'Data berhasil diupdate',
                    'type' => 'success',
                    'timeout' => 1000
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('updateAlertToast', [
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat update data',
                'type' => 'error',
                'timeout' => 1000
            ]);
        }
    }

    public function hitung()
    {
        $this->hasilHitungCPI = true;

        // Calculate MIN MAX values
        foreach ($this->kriteria as $k) {
            $subkriteria = \App\Models\AlternatifSubkriteria::join('subkriterias', 'alternatif_subkriterias.subkriteria_id', '=', 'subkriterias.id')
                ->where('subkriterias.kriteria_id', $k->id)
                ->select('alternatif_subkriterias.nilai')
                ->get();

            $nilai = $subkriteria->pluck('nilai')->toArray();

            $this->nilaiMin[$k->id] = min($nilai);
            $this->nilaiMax[$k->id] = max($nilai);
        }

        // Calculate nilai for each alternatif
        $hasil = [];
        foreach ($this->alternatif as $a) {
            $alternatif = [];
            foreach ($this->kriteria as $k) {
                $subkriteria = \App\Models\AlternatifSubkriteria::join('subkriterias', 'alternatif_subkriterias.subkriteria_id', '=', 'subkriterias.id')
                    ->where('alternatif_subkriterias.alternatif_id', $a->id)
                    ->where('subkriterias.kriteria_id', $k->id)
                    ->select('alternatif_subkriterias.*')
                    ->first();

                $alternatif[$k->id] = $subkriteria ? $subkriteria->nilai : 0;
            }
            $hasil[$a->id] = $alternatif;
        }

        $this->nilai = $hasil;

        $this->dispatch('updateAlertToast', [
            'title' => 'Berhasil',
            'text' => 'Perhitungan berhasil dilakukan',
            'type' => 'success',
            'timeout' => 1000
        ]);
    }

    public function selectAlternative($alternative)
    {
        $this->selectedAlternative = $alternative;
    }

    public function simpanPilihan()
    {
        if (!$this->selectedAlternative) {
            $this->dispatch('updateAlertToast', [
                'title' => 'Gagal',
                'text' => 'Pilih alternatif terlebih dahulu',
                'type' => 'error',
                'timeout' => 1000
            ]);
            return;
        }

        try {
            // Get selected alternative details
            $alternatif = \App\Models\Alternatif::where('id', $this->selectedAlternative)->first();

            // Check if the selected alternative exists
            if (!$alternatif) {
                $this->dispatch('updateAlertToast', [
                    'title' => 'Gagal',
                    'text' => 'Alternatif tidak ditemukan',
                    'type' => 'error',
                    'timeout' => 1000
                ]);
                return;
            }

            // Get ranking data
            $ranking = collect($this->alternatif)->map(function ($a) use ($alternatif) {
                $total = 0;
                foreach ($this->kriteria as $k) {
                    $subkriteria = \App\Models\AlternatifSubkriteria::join('subkriterias', 'alternatif_subkriterias.subkriteria_id', '=', 'subkriterias.id')
                        ->where('alternatif_subkriterias.alternatif_id', $a->id)
                        ->where('subkriterias.kriteria_id', $k->id)
                        ->select('alternatif_subkriterias.*')
                        ->first();

                    $nilaiAwal = $subkriteria ? $subkriteria->nilai : 0;
                    $min = $this->nilaiMin[$k->id];
                    $normalisasi = ($nilaiAwal / $min) * 100;
                    $total += $normalisasi * $k->bobot;
                }
                return [
                    'alternatif_id' => $a->id,
                    'nilai_cpi' => $total
                ];
            })->sortByDesc('nilai_cpi')->values();

            // Ensure ranking is not empty
            if ($ranking->isEmpty()) {
                $this->dispatch('updateAlertToast', [
                    'title' => 'Gagal',
                    'text' => 'Data ranking tidak ditemukan',
                    'type' => 'error',
                    'timeout' => 1000
                ]);
                return;
            }

            // Get rank of selected alternative
            $rank = $ranking->search(function ($item) use ($alternatif) {
                return $item['alternatif_id'] === $alternatif->id;
            }) + 1;

            // Generate unique code
            $kode_unik = 'CPI-' . date('YmdHis') . '-' . substr(uniqid(), -5);

            // Save to results
            \App\Models\Hasil::create([
                'alternatif_id' => $alternatif->id,
                'nilai_cpi' => $ranking->firstWhere('alternatif_id', $alternatif->id)['nilai_cpi'],
                'rank' => $rank,
                'kode_unik' => $kode_unik,
                'user_id' => User::first()->id
            ]);

            $this->dispatch('tambahAlert', [
                'title' => 'Berhasil',
                'text' => 'Data berhasil disimpan',
                'type' => 'success',
                'timeout' => 1000
            ]);

            // Reset selection
            $this->selectedAlternative = null;

            // return to hasil page
            return redirect()->route('hasil');
            
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Error saving data: ' . $e->getMessage());

            $this->dispatch('updateAlertToast', [
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                'type' => 'error',
                'timeout' => 1000
            ]);
        }
    }
}
