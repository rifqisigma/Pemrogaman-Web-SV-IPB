<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ump extends Model
{
    protected $fillable = ['provinsi_id', 'tahun', 'nominal'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
