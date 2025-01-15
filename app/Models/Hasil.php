<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $table = 'hasils';
    protected $fillable = ['alternatif_id', 'kode_unik', 'nilai_cpi', 'rank', 'user_id'];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }
}
