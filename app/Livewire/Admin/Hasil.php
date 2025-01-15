<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Hasil extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerpage()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.hasil', [
            'hasils' => \App\Models\Hasil::with('alternatif')->where('kode_unik', 'like', '%'.$this->search.'%')
                        ->orWhereHas('alternatif', function($query) {
                            $query->where('nama_alternatif', 'like', '%'.$this->search.'%');
                        })
                ->paginate($this->perpage)
        ])->layout('components.layouts.app', ['title' => 'Data Hasil']);
    }
}
