<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Controller untuk menampilkan daftar status permintaan di akun gudang.
 */
class PermintaanController extends Controller
{
    /**
     * Tampilkan daftar permintaan dengan ringkasan jumlah item & total qty.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

    $query = Permintaan::with(['peminta', 'details.bahan']);
        if ($status) {
            $query->where('status', $status);
        }

        // Deteksi kolom tanggal yang tersedia di tabel permintaan
        // Asumsi alternatif umum: 'tanggal', 'tanggal_request', 'created_at'
        $dateColumns = ['tanggal_permintaan', 'tanggal', 'tanggal_request', 'created_at'];
        $chosenDateColumn = null;
        $connection = $query->getModel()->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $query->getModel()->getTable();
        foreach ($dateColumns as $col) {
            if ($schema->hasColumn($table, $col)) {
                $chosenDateColumn = $col;
                break;
            }
        }

        if ($chosenDateColumn) {
            $query->orderByDesc($chosenDateColumn);
        }
        // Selalu urutkan kedua berdasarkan id agar stabil
        $query->orderByDesc('id');

        // Deteksi kolom menu (opsi umum)
        $menuColumns = ['menu', 'nama_menu', 'menu_diajukan'];
        $chosenMenuColumn = null;
        foreach ($menuColumns as $mc) {
            if ($schema->hasColumn($table, $mc)) {
                $chosenMenuColumn = $mc;
                break;
            }
        }

        $permintaan = $query->get();

        $availableStatuses = [
            Permintaan::STATUS_MENUNGGU,
            Permintaan::STATUS_DISETUJUI,
            Permintaan::STATUS_DITOLAK,
        ];

        return view('admin.permintaan.index', [
            'permintaan' => $permintaan,
            'availableStatuses' => $availableStatuses,
            'status' => $status,
            'chosenDateColumn' => $chosenDateColumn,
            'chosenMenuColumn' => $chosenMenuColumn,
        ]);
    }

    /**
     * Menyetujui permintaan dengan pengurangan stok.
     */
    public function approve(Permintaan $permintaan)
    {
        if (!$permintaan->isMenunggu()) {
            return back()->with('error', 'Permintaan tidak dalam status Menunggu.');
        }

        try {
            DB::transaction(function () use ($permintaan) {
                $permintaan->load('details');
                foreach ($permintaan->details as $detail) {
                    /** @var PermintaanDetail $detail */
                    $bahan = BahanBaku::lockForUpdate()->find($detail->bahan_id);
                    if (!$bahan) {
                        throw new \RuntimeException('Bahan ID ' . $detail->bahan_id . ' tidak ditemukan.');
                    }
                    if ($bahan->jumlah < $detail->jumlah) {
                        throw new \RuntimeException('Stok bahan "' . $bahan->nama . '" tidak cukup. (Butuh ' . $detail->jumlah . ', tersedia ' . $bahan->jumlah . ')');
                    }
                    $bahan->jumlah -= $detail->jumlah;
                    $bahan->save();
                }
                $permintaan->status = Permintaan::STATUS_DISETUJUI;
                $permintaan->save();
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
        }

        return back()->with('success', 'Permintaan #' . $permintaan->id . ' disetujui. Stok diperbarui.');
    }

    /**
     * Menolak permintaan dengan alasan opsional.
     */
    public function reject(Request $request, Permintaan $permintaan)
    {
        if (!$permintaan->isMenunggu()) {
            return back()->with('error', 'Permintaan tidak dalam status Menunggu.');
        }

        $validated = $request->validate([
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        if (Schema::hasColumn($permintaan->getTable(), 'alasan_penolakan')) {
            $permintaan->alasan_penolakan = $validated['alasan'] ?? null;
        }
        $permintaan->status = Permintaan::STATUS_DITOLAK;
        $permintaan->save();

        return back()->with('success', 'Permintaan #' . $permintaan->id . ' ditolak.' . (!empty($validated['alasan']) ? ' Alasan: ' . $validated['alasan'] : ''));
    }
}
