<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination as LivewireWithPagination;

class Alternatif extends Component
{
    use LivewireWithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;
    public $kode_alternatif, $nama_alternatif, $alternatif_id;

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
        return view('livewire.admin.alternatif', [
            'alternatifs' => \App\Models\Alternatif::where('nama_alternatif', 'like', '%'.$this->search.'%')->paginate($this->perpage)
        ])->layout('components.layouts.app', ['title' => 'Data Alternatif']);
    }

    public function resetInput()
    {
        $this->kode_alternatif = null;
        $this->nama_alternatif = null;
        $this->alternatif_id = null;

        $this->modal = false;
    }

    public function simpan()
    {
        $this->validate([
            'kode_alternatif' => 'required',
            'nama_alternatif' => 'required',
        ],[
            'kode_alternatif.required' => 'Kode Alternatif tidak boleh kosong',
            'nama_alternatif.required' => 'Nama Alternatif tidak boleh kosong',
        ]);

        \App\Models\Alternatif::create([
            'kode_alternatif' => $this->kode_alternatif,
            'nama_alternatif' => $this->nama_alternatif,
        ]);

        $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data Alternatif Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $alternatif = \App\Models\Alternatif::find($id);
        $this->alternatif_id = $alternatif->id;
        $this->kode_alternatif = $alternatif->kode_alternatif;
        $this->nama_alternatif = $alternatif->nama_alternatif;

        $this->modal = true;
    }

    public function update()
    {
        $this->validate([
            'kode_alternatif' => 'required',
            'nama_alternatif' => 'required',
        ],[
            'kode_alternatif.required' => 'Kode Alternatif tidak boleh kosong',
            'nama_alternatif.required' => 'Nama Alternatif tidak boleh kosong',
        ]);

        $alternatif = \App\Models\Alternatif::find($this->alternatif_id);
        $alternatif->update([
            'kode_alternatif' => $this->kode_alternatif,
            'nama_alternatif' => $this->nama_alternatif,
        ]);

        $this->dispatch('tambahAlert', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Alternatif Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function hapus($id)
    {
        \App\Models\Alternatif::find($id)->delete();

        $this->dispatch('tambahAlert', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Alternatif Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1000
        ]);
    }
}
