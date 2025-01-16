<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Models\Alternatif;
use App\Models\Hasil;


class Dashboard extends Component
{
    public $currentTime;

    public function mount()
    {
        $this->currentTime = now()->format('d M Y, H:i:s');
    }

    public function pollTime()
    {
        $this->currentTime = now()->format('d M Y, H:i:s');
    }
    
    public function render()
    {
        return view('livewire.dashboard',[
            'user' => User::count(),
            'kriteria' => Kriteria::count(),
            'subkriteria' => Subkriteria::count(),
            'alternatif' => Alternatif::count(),
            'hasil' => Hasil::count(),
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
