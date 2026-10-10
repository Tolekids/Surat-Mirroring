<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna
        $admin = User::firstOrCreate(
            ['email' => 'admin@kantor.go.id'],
            [
                'name' => 'Administrator Sistem',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $atasan = User::firstOrCreate(
            ['email' => 'atasan@kantor.go.id'],
            [
                'name' => 'Drs. H. Mulyadi, M.Si (Kepala Dinas)',
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'is_active' => true,
            ]
        );

        $staf = User::firstOrCreate(
            ['email' => 'staf@kantor.go.id'],
            [
                'name' => 'Siti Rahmawati (Staf Tata Usaha)',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'is_active' => true,
            ]
        );

        // 2. Siapkan direktori privat storage
        if (! Storage::disk('local')->exists('surat')) {
            Storage::disk('local')->makeDirectory('surat');
        }

        // 3. Helper untuk membuat PDF sampel valid
        $generateDummyPdf = function (string $judul, string $isi): string {
            $streamContent = "BT\n/F1 16 Tf\n50 720 Td\n(".addcslashes($judul, "()\n\r\\").") Tj\nET\n";
            $streamContent .= "BT\n/F1 11 Tf\n50 680 Td\n(".addcslashes($isi, "()\n\r\\").") Tj\nET\n";
            $length = strlen($streamContent);

            $pdf = "%PDF-1.4\n";
            $offsets = [];
            $offsets[1] = strlen($pdf);
            $pdf .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
            $offsets[2] = strlen($pdf);
            $pdf .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
            $offsets[3] = strlen($pdf);
            $pdf .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n";
            $offsets[4] = strlen($pdf);
            $pdf .= "4 0 obj\n<< /Length {$length} >>\nstream\n{$streamContent}endstream\nendobj\n";
            $offsets[5] = strlen($pdf);
            $pdf .= "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

            $xrefOffset = strlen($pdf);
            $pdf .= "xref\n0 6\n0000000000 65535 f \n";
            for ($i = 1; $i <= 5; $i++) {
                $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
            }
            $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";

            return $pdf;
        };

        // Surat 1: Undangan Rapat Koordinasi
        $path1 = 'surat/sample_surat_undangan.pdf';
        $pdfContent1 = $generateDummyPdf(
            'PEMERINTAH DAERAH - UNDANGAN RAPAT KOORDINASI',
            'Nomor: 005/142/Diskominfo/2026. Perihal: Undangan Evaluasi Mirroring Surat.'
        );
        Storage::disk('local')->put($path1, $pdfContent1);

        $surat1 = Surat::firstOrCreate(
            ['nomor_surat' => '005/142/Diskominfo/2026'],
            [
                'tanggal_surat' => now()->subDays(2)->format('Y-m-d'),
                'perihal' => 'Undangan Rapat Evaluasi Sistem Digital',
                'pengirim' => 'Sekretariat Daerah Bagian Organisasi',
                'kategori' => 'Undangan',
                'file_path' => $path1,
                'file_name' => 'undangan_rapat_evaluasi.pdf',
                'file_size' => strlen($pdfContent1),
                'keterangan' => 'Rapat dijadwalkan pada hari Kamis pukul 09.00 WIB di Ruang Rapat Utama.',
                'uploaded_by' => $staf->id,
            ]
        );

        // Surat 2: Nota Dinas Pengadaan Perangkat
        $path2 = 'surat/sample_surat_nota_dinas.pdf';
        $pdfContent2 = $generateDummyPdf(
            'NOTA DINAS - PERMOHONAN PENGADAAN PERANGKAT',
            'Nomor: 027/088/TU-ND/2026. Perihal: Pengadaan Scanner Dokumen Arsip Persuratan.'
        );
        Storage::disk('local')->put($path2, $pdfContent2);

        $surat2 = Surat::firstOrCreate(
            ['nomor_surat' => '027/088/TU-ND/2026'],
            [
                'tanggal_surat' => now()->subDay()->format('Y-m-d'),
                'perihal' => 'Permohonan Pengadaan Scanner Dokumen Persuratan',
                'pengirim' => 'Subbagian Umum dan Kepegawaian',
                'kategori' => 'Nota Dinas',
                'file_path' => $path2,
                'file_name' => 'nota_dinas_pengadaan_scanner.pdf',
                'file_size' => strlen($pdfContent2),
                'keterangan' => 'Untuk mendukung percepatan proses digitalisasi mirroring surat fisik masuk.',
                'uploaded_by' => $staf->id,
            ]
        );

        // Surat 3: Surat Masuk Kemenpan RB
        $path3 = 'surat/sample_surat_edaran.pdf';
        $pdfContent3 = $generateDummyPdf(
            'SURAT EDARAN - PEDOMAN KEARSIPAN DIGITAL KANTOR',
            'Nomor: B/1204/M.KT.01/2026. Perihal: Optimalisasi Arsip Digital Berkelanjutan.'
        );
        Storage::disk('local')->put($path3, $pdfContent3);

        $surat3 = Surat::firstOrCreate(
            ['nomor_surat' => 'B/1204/M.KT.01/2026'],
            [
                'tanggal_surat' => now()->format('Y-m-d'),
                'perihal' => 'Surat Edaran Optimalisasi Arsip Digital Berkelanjutan',
                'pengirim' => 'Kementerian PANRB',
                'kategori' => 'Surat Masuk',
                'file_path' => $path3,
                'file_name' => 'surat_edaran_arsip_digital.pdf',
                'file_size' => strlen($pdfContent3),
                'keterangan' => 'Harap menjadi perhatian seluruh pimpinan unit kerja.',
                'uploaded_by' => $staf->id,
            ]
        );

        // 4. Log Aktivitas Awal
        ActivityLog::create([
            'user_id' => $staf->id,
            'surat_id' => $surat1->id,
            'action' => 'upload',
            'description' => "Staf mengunggah surat baru nomor {$surat1->nomor_surat} ({$surat1->perihal})",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        ActivityLog::create([
            'user_id' => $staf->id,
            'surat_id' => $surat2->id,
            'action' => 'upload',
            'description' => "Staf mengunggah surat baru nomor {$surat2->nomor_surat} ({$surat2->perihal})",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        ActivityLog::create([
            'user_id' => $staf->id,
            'surat_id' => $surat3->id,
            'action' => 'upload',
            'description' => "Staf mengunggah surat baru nomor {$surat3->nomor_surat} ({$surat3->perihal})",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        ActivityLog::create([
            'user_id' => $atasan->id,
            'surat_id' => $surat1->id,
            'action' => 'preview',
            'description' => "Atasan melihat dan membaca preview digital surat {$surat1->nomor_surat}",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);
    }
}
