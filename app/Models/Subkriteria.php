<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    protected $table = 'subkriterias';
    protected $fillable = ['kriteria_id', 'nama_subkriteria', 'bobot', 'tren'];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function Alternatif()
    {
        return $this->hasMany(Alternatif::class);
    }
}
