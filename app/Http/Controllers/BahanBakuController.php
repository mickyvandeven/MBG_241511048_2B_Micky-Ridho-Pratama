<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk menambahkan bahan baku baru
 * Fokus hanya pada input data bahan baku untuk admin
 */
class BahanBakuController extends Controller
{
    /**
     * Menampilkan daftar bahan baku dengan status otomatis
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bahanBaku = BahanBaku::orderBy('tanggal_masuk', 'desc')->get();
        
        return view('admin.bahan-baku.index', compact('bahanBaku'));
    }

    /**
     * Menampilkan form untuk input bahan baku baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.bahan-baku.create');
    }

    /**
     * Menyimpan bahan baku baru ke database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dengan aturan yang jelas
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_kadaluarsa' => ['required', 'date', 'after:tanggal_masuk'],
        ], [
            'nama.required' => 'Nama bahan baku wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'satuan.required' => 'Satuan wajib diisi',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah tanggal masuk',
        ]);

        // Status akan dihitung otomatis berdasarkan aturan bisnis
        // Tidak perlu set status manual karena menggunakan accessor

        // Simpan ke database
        BahanBaku::create($validatedData);

        return redirect()->route('admin.bahan-baku.create')
            ->with('success', 'Bahan baku berhasil ditambahkan dengan status Tersedia');
    }

    /**
     * Menghapus bahan baku dari database
     * Hanya mengizinkan penghapusan bahan baku yang berstatus kadaluarsa
     * dan tidak sedang digunakan dalam permintaan
     * 
     * @param BahanBaku $bahanBaku
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BahanBaku $bahanBaku)
    {
        // Cek apakah bahan baku berstatus kadaluarsa
        if ($bahanBaku->status_otomatis !== BahanBaku::STATUS_KADALUARSA) {
            return redirect()->route('admin.bahan-baku.index')
                ->with('error', 'Bahan baku dengan status "' . $bahanBaku->status_otomatis . '" tidak dapat dihapus. Hanya bahan baku yang sudah kadaluarsa yang dapat dihapus.');
        }

        // Cek apakah bahan baku masih digunakan dalam permintaan
        try {
            // Cek di tabel permintaan_detail
            $isUsedInPermintaan = DB::table('permintaan_detail')
                ->where('bahan_id', $bahanBaku->id)
                ->exists();

            if ($isUsedInPermintaan) {
                return redirect()->route('admin.bahan-baku.index')
                    ->with('error', 'Bahan baku "' . $bahanBaku->nama . '" tidak dapat dihapus karena masih digunakan dalam data permintaan. Hapus data permintaan terkait terlebih dahulu.');
            }

            // Simpan nama untuk pesan konfirmasi
            $namaBahan = $bahanBaku->nama;
            
            // Hapus bahan baku
            $bahanBaku->delete();

            return redirect()->route('admin.bahan-baku.index')
                ->with('success', "Bahan baku '{$namaBahan}' berhasil dihapus karena sudah kadaluarsa.");

        } catch (\Exception $e) {
            return redirect()->route('admin.bahan-baku.index')
                ->with('error', 'Terjadi kesalahan saat menghapus bahan baku. Bahan baku mungkin masih digunakan dalam sistem.');
        }
    }
}
