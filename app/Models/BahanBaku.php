<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model BahanBaku untuk tabel 'bahan_baku'
 * Mengelola data bahan baku dengan status dan atribut lengkap
 */
class BahanBaku extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'bahan_baku';

    /**
     * Menonaktifkan timestamps karena tabel tidak memiliki created_at/updated_at
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal
     */
    protected $fillable = [
        'nama',
        'kategori', 
        'jumlah',
        'satuan',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
        'status'
    ];

    /**
     * Casting atribut ke tipe data yang sesuai
     */
    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'jumlah' => 'integer'
    ];

    /**
     * Konstanta untuk status bahan baku
     */
    const STATUS_TERSEDIA = 'Tersedia';
    const STATUS_HABIS = 'Habis';
    const STATUS_KADALUARSA = 'Kadaluarsa';

    /**
     * Mengecek apakah bahan baku masih tersedia
     * 
     * @return bool
     */
    public function isTersedia(): bool
    {
        return $this->status === self::STATUS_TERSEDIA;
    }

    /**
     * Mengecek apakah bahan baku sudah kadaluarsa
     * 
     * @return bool
     */
    public function isKadaluarsa(): bool
    {
        return $this->tanggal_kadaluarsa < now()->toDateString();
    }

    /**
     * Scope untuk bahan baku yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA);
    }
}