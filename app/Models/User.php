<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User untuk tabel 'user'
 * Menggunakan prinsip clean code dengan property yang jelas dan metode yang fokus
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'user';

    /**
     * Menonaktifkan timestamps karena tabel tidak memiliki created_at/updated_at
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'role'
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Mengecek apakah user memiliki role tertentu
     * 
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
