<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Surat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SuratController extends Controller
{
    /**
     * @var list<string>
     */
    public const KATEGORI_LIST = [
        'Surat Masuk',
        'Surat Keluar',
        'Nota Dinas',
        'Undangan',
        'Surat Edaran',
        'Lainnya',
    ];

    public function index(Request $request): View
    {
        $query = Surat::with('uploader');

        // Pencarian teks (Nomor surat, Perihal, Pengirim, Keterangan)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%")
                    ->orWhere('pengirim', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($kategori = $request->input('kategori')) {
            $query->where('kategori', $kategori);
        }

        // Filter Rentang Tanggal Surat
        if ($dari = $request->input('dari_tanggal')) {
            $query->whereDate('tanggal_surat', '>=', $dari);
        }

        if ($sampai = $request->input('sampai_tanggal')) {
            $query->whereDate('tanggal_surat', '<=', $sampai);
        }

        // Urutan
        $sort = $request->input('sort', 'terbaru');
        match ($sort) {
            'terlama' => $query->oldest('tanggal_surat'),
            'nomor' => $query->orderBy('nomor_surat', 'asc'),
            default => $query->latest('tanggal_surat')->latest('id'),
        };

        $surats = $query->paginate(10)->withQueryString();
        $kategoriList = self::KATEGORI_LIST;

        return view('surat.index', compact('surats', 'kategoriList'));
    }

    public function create(): View
    {
        $kategoriList = self::KATEGORI_LIST;

        return view('surat.create', compact('kategoriList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100', 'unique:surat,nomor_surat'],
            'tanggal_surat' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'pengirim' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:50'],
            'file_surat' => ['required', 'file', 'mimes:pdf', 'max:10240'], // Max 10MB
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'nomor_surat.unique' => 'Nomor surat ini sudah terdaftar dalam sistem.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'pengirim.required' => 'Asal pengirim surat wajib diisi.',
            'kategori.required' => 'Kategori surat wajib dipilih.',
            'file_surat.required' => 'Berkas PDF surat wajib diunggah.',
            'file_surat.mimes' => 'Berkas harus dalam format PDF.',
            'file_surat.max' => 'Ukuran berkas PDF maksimal 10 MB.',
        ]);

        $file = $request->file('file_surat');
        $storedPath = $file->store('surat', 'local');

        $surat = Surat::create([
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'perihal' => $validated['perihal'],
            'pengirim' => $validated['pengirim'],
            'kategori' => $validated['kategori'],
            'file_path' => $storedPath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'keterangan' => $validated['keterangan'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        ActivityLog::log(
            action: 'upload',
            description: "Surat baru nomor {$surat->nomor_surat} ({$surat->perihal}) berhasil diunggah.",
            suratId: $surat->id
        );

        return redirect()->route('surat.show', $surat)
            ->with('success', "Surat nomor {$surat->nomor_surat} berhasil disimpan dan siap ditinjau.");
    }

    public function show(Surat $surat): View
    {
        $surat->load(['uploader', 'activityLogs.user']);

        // Catat audit log jika atasan membaca / membuka surat
        if (Auth::user()?->isAtasan()) {
            ActivityLog::log(
                action: 'preview',
                description: 'Atasan '.Auth::user()->name." membaca salinan digital surat {$surat->nomor_surat}.",
                suratId: $surat->id
            );
        }

        return view('surat.show', compact('surat'));
    }

    public function edit(Surat $surat): View
    {
        $kategoriList = self::KATEGORI_LIST;

        return view('surat.edit', compact('surat', 'kategoriList'));
    }

    public function update(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100', 'unique:surat,nomor_surat,'.$surat->id],
            'tanggal_surat' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'pengirim' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:50'],
            'file_surat' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'nomor_surat.unique' => 'Nomor surat ini sudah terdaftar dalam sistem.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'pengirim.required' => 'Asal pengirim surat wajib diisi.',
            'file_surat.mimes' => 'Berkas pengganti harus berformat PDF.',
            'file_surat.max' => 'Ukuran berkas PDF maksimal 10 MB.',
        ]);

        $fileChanged = false;

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');

            // Hapus file lama jika ada
            if (Storage::disk('local')->exists($surat->file_path)) {
                Storage::disk('local')->delete($surat->file_path);
            }

            $surat->file_path = $file->store('surat', 'local');
            $surat->file_name = $file->getClientOriginalName();
            $surat->file_size = $file->getSize();
            $fileChanged = true;
        }

        $surat->nomor_surat = $validated['nomor_surat'];
        $surat->tanggal_surat = $validated['tanggal_surat'];
        $surat->perihal = $validated['perihal'];
        $surat->pengirim = $validated['pengirim'];
        $surat->kategori = $validated['kategori'];
        $surat->keterangan = $validated['keterangan'] ?? null;
        $surat->save();

        $actionNote = $fileChanged
            ? "Data metadata dan berkas PDF surat {$surat->nomor_surat} diperbarui."
            : "Data metadata surat {$surat->nomor_surat} diperbarui.";

        ActivityLog::log(
            action: 'update',
            description: $actionNote,
            suratId: $surat->id
        );

        return redirect()->route('surat.show', $surat)
            ->with('success', "Informasi surat nomor {$surat->nomor_surat} berhasil diperbarui.");
    }

    public function destroy(Surat $surat): RedirectResponse
    {
        $nomorSurat = $surat->nomor_surat;
        $perihal = $surat->perihal;

        // Hapus file privat
        if (Storage::disk('local')->exists($surat->file_path)) {
            Storage::disk('local')->delete($surat->file_path);
        }

        $surat->delete();

        ActivityLog::log(
            action: 'delete',
            description: "Surat nomor {$nomorSurat} ({$perihal}) telah dihapus dari sistem."
        );

        return redirect()->route('surat.index')
            ->with('success', "Surat nomor {$nomorSurat} berhasil dihapus.");
    }

    public function streamPdf(Surat $surat): Response
    {
        if (! Storage::disk('local')->exists($surat->file_path)) {
            abort(404, 'Berkas salinan fisik PDF tidak ditemukan di server.');
        }

        $fileName = $surat->file_name ?: "surat-{$surat->id}.pdf";

        return Storage::disk('local')->response(
            $surat->file_path,
            $fileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$fileName.'"',
                'Cache-Control' => 'private, max-age=3600',
            ]
        );
    }

    public function downloadPdf(Surat $surat): Response
    {
        if (! Storage::disk('local')->exists($surat->file_path)) {
            abort(404, 'Berkas salinan fisik PDF tidak ditemukan di server.');
        }

        $user = Auth::user();
        ActivityLog::log(
            action: 'download',
            description: "Pengguna {$user->name} ({$user->role}) mengunduh berkas PDF surat {$surat->nomor_surat}.",
            suratId: $surat->id
        );

        $downloadName = $surat->file_name ?: 'surat-'.str_replace('/', '_', $surat->nomor_surat).'.pdf';

        return Storage::disk('local')->download($surat->file_path, $downloadName);
    }
}
