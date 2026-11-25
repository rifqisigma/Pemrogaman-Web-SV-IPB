<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provinsi_id',
        'bidang_kerja',
        'jabatan',
        'status_pekerjaan',
        'gaji_nominal',
        'gaji_sebelumnya',
        'alasan_pembaruan',
        'tahun',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'gaji_nominal' => 'decimal:2',
            'gaji_sebelumnya' => 'decimal:2',
            'tahun' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
