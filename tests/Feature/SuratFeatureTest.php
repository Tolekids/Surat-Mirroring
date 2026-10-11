<?php

namespace Tests\Feature;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $responseSurat = $this->get('/surat');
        $responseSurat->assertRedirect('/login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'staf@kantor.go.id',
            'password' => Hash::make('password'),
            'role' => 'staf',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'staf@kantor.go.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_staf_can_access_upload_surat_page(): void
    {
        $staf = User::factory()->create([
            'role' => 'staf',
            'is_active' => true,
        ]);

        $response = $this->actingAs($staf)->get('/surat/create');
        $response->assertStatus(200);
        $response->assertSee('Unggah Salinan Digital Surat');
    }

    public function test_atasan_cannot_access_upload_surat_page(): void
    {
        $atasan = User::factory()->create([
            'role' => 'atasan',
            'is_active' => true,
        ]);

        $response = $this->actingAs($atasan)->get('/surat/create');
        $response->assertStatus(403);
    }

    public function test_staf_can_upload_pdf_surat_and_activity_is_logged(): void
    {
        Storage::fake('local');

        $staf = User::factory()->create([
            'role' => 'staf',
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->create('surat_keputusan.pdf', 120, 'application/pdf');

        $response = $this->actingAs($staf)->post('/surat', [
            'nomor_surat' => 'SK/001/DISPORA/2026',
            'tanggal_surat' => '2026-10-10',
            'perihal' => 'Penetapan Panitia Hari Olahraga Nasional',
            'pengirim' => 'Dinas Pemuda dan Olahraga',
            'kategori' => 'Surat Keluar',
            'file_surat' => $file,
            'keterangan' => 'Arsip digital tembusan pimpinan.',
        ]);

        $surat = Surat::where('nomor_surat', 'SK/001/DISPORA/2026')->first();
        $this->assertNotNull($surat);
        $response->assertRedirect(route('surat.show', $surat));

        // Verifikasi file disimpan di private disk
        Storage::disk('local')->assertExists($surat->file_path);

        // Verifikasi log upload tercatat
        $this->assertDatabaseHas('activity_logs', [
            'surat_id' => $surat->id,
            'action' => 'upload',
            'user_id' => $staf->id,
        ]);
    }

    public function test_atasan_can_view_surat_and_preview_is_logged(): void
    {
        Storage::fake('local');

        $staf = User::factory()->create(['role' => 'staf', 'is_active' => true]);
        $atasan = User::factory()->create(['name' => 'Bapak Kepala', 'role' => 'atasan', 'is_active' => true]);

        $dummyContent = '%PDF-1.4 dummy file';
        Storage::disk('local')->put('surat/test.pdf', $dummyContent);

        $surat = Surat::create([
            'nomor_surat' => '005/TEST/2026',
            'tanggal_surat' => '2026-10-10',
            'perihal' => 'Undangan Rapat Pimpinan',
            'pengirim' => 'Sekretariat Daerah',
            'kategori' => 'Undangan',
            'file_path' => 'surat/test.pdf',
            'file_name' => 'test.pdf',
            'file_size' => strlen($dummyContent),
            'uploaded_by' => $staf->id,
        ]);

        $response = $this->actingAs($atasan)->get("/surat/{$surat->id}");
        $response->assertStatus(200);
        $response->assertSee('Undangan Rapat Pimpinan');
        $response->assertSee('Penayangan Salinan Digital (Mirroring)');

        // Verifikasi audit log preview dibuat saat atasan membuka surat
        $this->assertDatabaseHas('activity_logs', [
            'surat_id' => $surat->id,
            'action' => 'preview',
            'user_id' => $atasan->id,
        ]);
    }

    public function test_stream_and_download_pdf_securely(): void
    {
        Storage::fake('local');

        $staf = User::factory()->create(['role' => 'staf', 'is_active' => true]);
        $atasan = User::factory()->create(['name' => 'Bapak Kepala', 'role' => 'atasan', 'is_active' => true]);

        $dummyContent = '%PDF-1.4 sample content';
        Storage::disk('local')->put('surat/sample_doc.pdf', $dummyContent);

        $surat = Surat::create([
            'nomor_surat' => '123/STREAM/2026',
            'tanggal_surat' => '2026-10-10',
            'perihal' => 'Uji Streaming PDF',
            'pengirim' => 'Bagian Hukum',
            'kategori' => 'Nota Dinas',
            'file_path' => 'surat/sample_doc.pdf',
            'file_name' => 'sample_doc.pdf',
            'file_size' => strlen($dummyContent),
            'uploaded_by' => $staf->id,
        ]);

        // Tamu tidak bisa akses stream langsung
        $guestResponse = $this->get("/surat/{$surat->id}/stream");
        $guestResponse->assertRedirect('/login');

        // Atasan dapat membaca stream PDF
        $streamResponse = $this->actingAs($atasan)->get("/surat/{$surat->id}/stream");
        $streamResponse->assertStatus(200);
        $this->assertEquals('application/pdf', $streamResponse->headers->get('content-type'));

        // Atasan dapat mengunduh PDF dan tercatat di activity log
        $downloadResponse = $this->actingAs($atasan)->get("/surat/{$surat->id}/download");
        $downloadResponse->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'surat_id' => $surat->id,
            'action' => 'download',
            'user_id' => $atasan->id,
        ]);
    }

    public function test_atasan_or_kepala_bidang_cannot_access_activity_logs_page(): void
    {
        $atasan = User::factory()->create([
            'name' => 'Kepala Bidang',
            'role' => 'atasan',
            'is_active' => true,
        ]);

        // Atasan / Kepala Bidang tidak boleh mengakses page riwayat aktivitas (403 Forbidden)
        $response = $this->actingAs($atasan)->get('/activity-logs');
        $response->assertStatus(403);

        // Sidebar dan Dashboard tidak menampilkan menu/link ke page riwayat aktivitas
        $dashboardResponse = $this->actingAs($atasan)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertDontSee(route('activity_logs.index'));
    }

    public function test_admin_can_access_activity_logs_page(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Sistem',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/activity-logs');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Aktivitas &amp; Audit Trail', false);
    }
}

