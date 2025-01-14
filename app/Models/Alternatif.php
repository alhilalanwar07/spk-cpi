<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatifs';
    protected $fillable = ['nama_alternatif', 'kode_alternatif'];

    public function AlternatifKriteria()
    {
        return $this->hasMany(AlternatifKriteria::class);
    }
}
