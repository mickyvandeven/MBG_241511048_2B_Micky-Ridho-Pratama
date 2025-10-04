<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

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
}
