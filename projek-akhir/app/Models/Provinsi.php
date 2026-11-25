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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function wageLogs()
    {
        return $this->hasMany(WageLog::class);
    }
}
