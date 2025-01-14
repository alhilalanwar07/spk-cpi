<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'penggunas';
    protected $fillable = ['nama', 'alamat', 'telepon', 'luas_lahan', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
