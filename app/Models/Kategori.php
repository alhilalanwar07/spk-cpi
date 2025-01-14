<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $fillable = ['nama', 'slug'];

    // slug oto generate
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Generate slug jika kosong atau jika nama berubah
            if (!$model->slug || $model->isDirty('nama')) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }
}
