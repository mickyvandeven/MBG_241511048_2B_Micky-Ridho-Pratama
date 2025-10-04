<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller untuk menangani halaman client/dapur
 * Menggunakan prinsip single responsibility - fokus pada fungsi client saja
 */
class ClientController extends Controller
{
    /**
     * Menampilkan dashboard client untuk role dapur
     * 
     * @return \Illuminate\View\View
     */
    public function clientDashboard()
    {
        return view('client.dashboard');
    }
}
