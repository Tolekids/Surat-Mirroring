<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total' => Surat::count(),
            'surat_masuk' => Surat::where('kategori', 'Surat Masuk')->count(),
            'surat_keluar' => Surat::where('kategori', 'Surat Keluar')->count(),
            'nota_dinas' => Surat::where('kategori', 'Nota Dinas')->count(),
            'undangan' => Surat::where('kategori', 'Undangan')->count(),
            'minggu_ini' => Surat::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        $suratTerbaru = Surat::with('uploader')
            ->latest()
            ->take(6)
            ->get();

        $aktivitasTerbaru = ActivityLog::with(['user', 'surat'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('stats', 'suratTerbaru', 'aktivitasTerbaru', 'user'));
    }
}
