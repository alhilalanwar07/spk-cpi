<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatifs';
    protected $fillable = ['nama_alternatif', 'alamat', 'telepon', 'luas_lahan', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function AlternatifKriteria()
    {
        return $this->hasMany(AlternatifKriteria::class);
    }
}
