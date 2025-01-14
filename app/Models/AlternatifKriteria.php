<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlternatifKriteria extends Model
{
    protected $table = 'alternatif_kriterias';
    protected $fillable = ['alternatif_id', 'kriteria_id', 'subkriteria_id', 'nilai'];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }
}
