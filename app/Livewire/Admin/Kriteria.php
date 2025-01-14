<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Kriteria extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $nama_kriteria, $bobot, $tren, $kriteria_id;

    public $modal = true;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerpage()
    {
        $this->resetPage();
    }

    public function setPerPage($value)
    {
        $this->perpage = $value;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.kriteria', [
            'kriterias' => \App\Models\Kriteria::where('nama_kriteria', 'like', '%'.$this->search.'%')->paginate($this->perpage)
        ])->layout('components.layouts.app', ['title' => 'Data Kriteria']);
    }

    public function resetInput()
    {
        $this->nama_kriteria = null;
        $this->bobot = null;
        $this->tren = null;
        $this->kriteria_id = null;

        $this->modal = false;
    }

    public function simpan()
    {
        $this->validate([
            'nama_kriteria' => 'required',
            'bobot' => 'required',
            'tren' => 'required',
        ],[
            'nama_kriteria.required' => 'Nama Kriteria tidak boleh kosong',
            'bobot.required' => 'Bobot tidak boleh kosong',
            'tren.required' => 'Tren tidak boleh kosong',
        ]);

        \App\Models\Kriteria::create([
            'nama_kriteria' => $this->nama_kriteria,
            'bobot' => $this->bobot,
            'tren' => $this->tren,
        ]);


        // jika berhasil di tambah
        $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data Kriteria Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $kriteria = \App\Models\Kriteria::find($id);
        $this->kriteria_id = $kriteria->id;
        $this->nama_kriteria = $kriteria->nama_kriteria;
        $this->bobot = $kriteria->bobot;
        $this->tren = $kriteria->tren;

        $this->modal = true;
    }

    public function update()
    {
        $this->validate([
            'nama_kriteria' => 'required',
            'bobot' => 'required',
            'tren' => 'required',
        ],[
            'nama_kriteria.required' => 'Nama Kriteria tidak boleh kosong',
            'bobot.required' => 'Bobot tidak boleh kosong',
            'tren.required' => 'Tren tidak boleh kosong',
        ]);

        $kriteria = \App\Models\Kriteria::find($this->kriteria_id);
        $kriteria->update([
            'nama_kriteria' => $this->nama_kriteria,
            'bobot' => $this->bobot,
            'tren' => $this->tren,
        ]);


        // jika berhasil di update
        $this->dispatch('tambahAlert', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Kriteria Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function hapus($id)
    {
        // Check if there are any related records in the 'prodi' table
        $relatedSubKriteria = \App\Models\SubKriteria::where('kriteria_id', $id)->first();
        $relatedAlternatifKriteria = \App\Models\AlternatifKriteria::where('kriteria_id', $id)->first();

        if ($relatedSubKriteria || $relatedAlternatifKriteria) {
            // If there are related records, show an alert
            $this->dispatch('tambahAlert', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Data Kriteria tidak bisa dihapus karena terkait dengan data lain',
                'type'      => 'error',
                'timeout'   => 1000
            ]);
            return;
        }

        // If no related records, proceed with deletion
        \App\Models\Kriteria::find($id)->delete();

        // jika berhasil di hapus
        $this->dispatch('tambahAlert', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Kriteria Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1000
        ]);
    }
    
}
