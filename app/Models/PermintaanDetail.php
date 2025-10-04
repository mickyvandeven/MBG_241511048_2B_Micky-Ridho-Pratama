<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model PermintaanDetail merepresentasikan tabel 'permintaan_detail'.
 * Asumsi kolom minimal: id, permintaan_id, bahan_id, jumlah, keterangan.
 */
class PermintaanDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_detail';
    public $timestamps = false;

    protected $fillable = [
        'permintaan_id',
        'bahan_id',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

    public function bahan()
    {
        // Deteksi foreign key dinamis untuk bahan
        $connection = $this->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $this->getTable();
        
        $candidates = ['bahan_id', 'bahan_baku_id', 'id_bahan', 'id_bahan_baku'];
        foreach ($candidates as $fk) {
            if ($schema->hasColumn($table, $fk)) {
                return $this->belongsTo(BahanBaku::class, $fk);
            }
        }
        // default fallback
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    /**
     * Accessor untuk kuantitas yang fleksibel
     */
    public function getJumlahAttribute()
    {
        $connection = $this->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $this->getTable();
        
        $candidates = ['jumlah', 'qty', 'kuantitas', 'jumlah_diminta'];
        foreach ($candidates as $col) {
            if ($schema->hasColumn($table, $col) && isset($this->attributes[$col])) {
                return (int) $this->attributes[$col];
            }
        }
        return 0;
    }
}
