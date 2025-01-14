<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Kategori extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;

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

    public $nama;
    public $modal = true;
    public $kategori_id;

    public function render()
    {
        return view('livewire.admin.kategori', [
            'kategoris' => \App\Models\Kategori::where('nama', 'like', '%' . $this->search . '%')->paginate($this->perpage)
        ])->layout('components.layouts.app', ['title' => 'Kategori']);
    }
    

    public function resetInput()
    {
        $this->nama = null;
        $this->kategori_id = null;
        $this->modal = false;
    }

    public function simpan()
    {
        $this->validate([
            'nama' => 'required',
        ],[
            'nama.required' => 'Nama Kategori tidak boleh kosong',
        ]);

        \App\Models\Kategori::create([
            'nama' => $this->nama,
        ]);

        $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data Kategori Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $kategori = \App\Models\Kategori::find($id);
        $this->kategori_id = $kategori->id;
        $this->nama = $kategori->nama;
        $this->modal = true;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
        ],[
            'nama.required' => 'Nama Kategori tidak boleh kosong',
        ]);

        $kategori = \App\Models\Kategori::find($this->kategori_id);
        $kategori->update([
            'nama' => $this->nama,
        ]);

        $this->dispatch('tambahAlert', [
            'title'     => 'Update data berhasil',
            'text'      => 'Data Kategori Berhasil Diupdate',
            'type'      => 'success',
            'timeout'   => 1000
        ]);

        $this->resetInput();
    }

    public function hapus($id)
    {
        $relatedPengguna = \App\Models\Pengguna::where('kategori_id', $id)->count();
        if ($relatedPengguna > 0) {
            $this->dispatch('tambahAlert', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Data Kategori tidak bisa dihapus karena terkait dengan data Pengguna',
                'type'      => 'error',
                'timeout'   => 1000
            ]);
            return;
        }
        \App\Models\Kategori::find($id)->delete();

        $this->dispatch('tambahAlert', [
            'title'     => 'Hapus data berhasil',
            'text'      => 'Data Kategori Berhasil Dihapus',
            'type'      => 'success',
            'timeout'   => 1000
        ]);
    }
}
