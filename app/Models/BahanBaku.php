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
    const STATUS_SEGERA_KADALUARSA = 'Segera Kadaluarsa';

    /**
     * Menghitung dan mengembalikan status bahan baku berdasarkan aturan bisnis:
     * - Habis: jika jumlah = 0
     * - Kadaluarsa: jika hari_ini >= tanggal_kadaluarsa
     * - Segera Kadaluarsa: jika tanggal_kadaluarsa - hari_ini <= 3 dan stok > 0
     * - Tersedia: jika stok > 0 dan tidak masuk kondisi di atas
     * 
     * @return string
     */
    public function getStatusOtomatisAttribute(): string
    {
        $today = now()->toDateString();
        $expiredDate = $this->tanggal_kadaluarsa->toDateString();
        $daysUntilExpired = now()->diffInDays($this->tanggal_kadaluarsa, false);

        // Rule 1: Habis jika jumlah = 0
        if ($this->jumlah == 0) {
            return self::STATUS_HABIS;
        }

        // Rule 2: Kadaluarsa jika hari_ini >= tanggal_kadaluarsa
        if ($today >= $expiredDate) {
            return self::STATUS_KADALUARSA;
        }

        // Rule 3: Segera Kadaluarsa jika tanggal_kadaluarsa - hari_ini <= 3 dan stok > 0
        if ($daysUntilExpired <= 3 && $this->jumlah > 0) {
            return self::STATUS_SEGERA_KADALUARSA;
        }

        // Rule 4: Tersedia jika stok > 0 dan tidak masuk kondisi di atas
        return self::STATUS_TERSEDIA;
    }

    /**
     * Mengecek apakah bahan baku masih tersedia
     * 
     * @return bool
     */
    public function isTersedia(): bool
    {
        return $this->status_otomatis === self::STATUS_TERSEDIA;
    }

    /**
     * Mengecek apakah bahan baku sudah kadaluarsa
     * 
     * @return bool
     */
    public function isKadaluarsa(): bool
    {
        return $this->status_otomatis === self::STATUS_KADALUARSA;
    }

    /**
     * Mengecek apakah bahan baku segera kadaluarsa
     * 
     * @return bool
     */
    public function isSegeraKadaluarsa(): bool
    {
        return $this->status_otomatis === self::STATUS_SEGERA_KADALUARSA;
    }

    /**
     * Mengecek apakah bahan baku habis
     * 
     * @return bool
     */
    public function isHabis(): bool
    {
        return $this->status_otomatis === self::STATUS_HABIS;
    }

    /**
     * Scope untuk bahan baku yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->whereRaw('jumlah > 0 AND tanggal_kadaluarsa > CURDATE() AND DATEDIFF(tanggal_kadaluarsa, CURDATE()) > 3');
    }

    /**
     * Scope untuk bahan baku yang segera kadaluarsa
     */
    public function scopeSegeraKadaluarsa($query)
    {
        return $query->whereRaw('jumlah > 0 AND tanggal_kadaluarsa > CURDATE() AND DATEDIFF(tanggal_kadaluarsa, CURDATE()) <= 3');
    }
}