<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriterias';
    protected $fillable = ['nama_kriteria', 'bobot', 'tren'];

    public function subkriteria()
    {
        return $this->hasMany(Subkriteria::class);
    }

    public function AlternatifKriteria()
    {
        return $this->hasMany(AlternatifKriteria::class);
    }
}
