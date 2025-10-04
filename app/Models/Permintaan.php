<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Permintaan merepresentasikan tabel 'permintaan'.
 * Asumsi kolom minimal: id, user_id (peminta), tanggal_permintaan, status.
 * Relasi: hasMany PermintaanDetail, belongsTo User.
 */
class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaan';

    // Asumsi tabel tidak memiliki timestamps; ubah jika berbeda.
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tanggal_permintaan',
        'status',
        'alasan_penolakan', // opsional jika kolom tersedia
    ];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    // Konstanta status (sesuaikan jika di DB berbeda)
    const STATUS_MENUNGGU = 'Menunggu';
    const STATUS_DISETUJUI = 'Disetujui';
    const STATUS_DITOLAK = 'Ditolak';
    // Tambahan potensial untuk future flow
    const STATUS_DIPROSES = 'Diproses';
    const STATUS_SELESAI = 'Selesai';

    public function isMenunggu(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function details()
    {
        return $this->hasMany(PermintaanDetail::class, 'permintaan_id');
    }

    /**
     * Relasi dinamis ke user/peminta. Mendeteksi foreign key yang ada di attributes.
     */
    public function peminta()
    {
        $connection = $this->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $this->getTable();
        
        $candidates = ['pemohon_id','peminta_id','user_id'];
        foreach ($candidates as $fk) {
            if ($schema->hasColumn($table, $fk)) {
                return $this->belongsTo(User::class, $fk);
            }
        }
        // default fallback
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor nama peminta yang aman.
     */
    public function getNamaPemintaAttribute(): string
    {
        $rel = $this->peminta; // triggers relation
        return $rel?->name ?? 'Tidak Diketahui';
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->details->count();
    }

    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->details->sum('jumlah');
    }

    /**
     * Ringkasan daftar bahan: array [nama_bahan => total_jumlah]
     */
    public function getRingkasanBahanAttribute(): array
    {
        $summary = [];
        foreach ($this->details as $d) {
            if (!$d->bahan) {
                continue; // skip jika relasi hilang
            }
            $nama = $d->bahan->nama;
            $summary[$nama] = ($summary[$nama] ?? 0) + (int) $d->jumlah;
        }
        return $summary;
    }

    /**
     * Ringkasan dalam format string: "Nama1 (10), Nama2 (5)"
     */
    public function getRingkasanBahanStringAttribute(): string
    {
        $parts = [];
        foreach ($this->ringkasan_bahan as $nama => $qty) {
            $parts[] = $nama . ' (' . $qty . ')';
        }
        return implode(', ', $parts);
    }
}
