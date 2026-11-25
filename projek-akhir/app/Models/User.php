<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'provinsi_id',
        'bidang_kerja',
        'jabatan',
        'status_pekerjaan',
        'gaji_pokok',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gaji_pokok' => 'decimal:2',
        ];
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function wageLogs()
    {
        return $this->hasMany(WageLog::class)->orderBy('created_at', 'desc');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
