<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $kriteria = \App\Models\Kriteria::count();
        $subkriteria = \App\Models\Subkriteria::count();
        $alternatif = \App\Models\Alternatif::count();
        $hasil = \App\Models\Hasil::count();
        $user = \App\Models\User::count();
        return view('home', compact('kriteria', 'subkriteria', 'alternatif', 'hasil', 'user'));
    }
}
