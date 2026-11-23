<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $fillable = ['kode_provinsi', 'nama_provinsi'];

    public function umps()
    {
        return $this->hasMany(Ump::class);
    }
}
