<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlternatifSubkriteria extends Model
{
    protected $table = 'alternatif_subkriterias';
    protected $fillable = ['alternatif_id', 'subkriteria_id', 'nilai'];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }
}
