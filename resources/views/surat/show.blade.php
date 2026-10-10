@extends('layouts.app')

@section('title', 'Mirroring Surat - ' . $surat->nomor_surat . ' · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <div class="d-flex align-items-center gap-2 mb-2">
      @if($surat->kategori === 'Surat Masuk')
        <span class="chip g">Surat Masuk</span>
      @elseif(in_array($surat->kategori, ['Nota Dinas', 'Surat Edaran']))
        <span class="chip n">{{ $surat->kategori }}</span>
      @else
        <span class="chip">{{ $surat->kategori }}</span>
      @endif
      <span style="color: var(--muted); font-size: 12.5px;">
        <i class="bi bi-clock me-1"></i>Diunggah: {{ $surat->created_at->format('d/m/Y H:i') }}
      </span>
    </div>
    <h1>{{ $surat->nomor_surat }}</h1>
  </div>

  <div class="d-flex flex-wrap gap-2 align-items-center">
    <a href="{{ route('surat.index') }}" class="btn-custom-outline">
      <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Kembali
    </a>

    <!-- Tombol Download Dokumen -->
    <a href="{{ route('surat.download', $surat) }}" class="btn-custom-outline" title="Unduh Berkas Salinan">
      <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
      Unduh PDF
    </a>

    <!-- Tombol Layar Penuh Viewer -->
    <button type="button" class="btn-custom-outline" onclick="toggleFullscreenViewer()" title="Tampilkan Layar Penuh">
      <svg viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
      Layar Penuh
    </button>

    @if(auth()->user()->isStaf() || auth()->user()->isAdmin())
      <a href="{{ route('surat.edit', $surat) }}" class="btn-custom-outline" title="Edit Metadata Dokumen">
        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit
      </a>

      <form action="{{ route('surat.destroy', $surat) }}" method="POST" class="d-inline m-0"
            onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini beserta berkas digitalnya? Tindakan ini tidak dapat dibatalkan.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-custom-danger" title="Hapus Berkas Surat">
          <svg viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
          Hapus
        </button>
      </form>
    @endif
  </div>
</div>

<div class="row g-4 mb-5">
  <!-- Kolom Kiri: Metadata Dokumen & Audit Log (col-lg-4) -->
  <div class="col-lg-4">
    <!-- Card Metadata Surat -->
    <div class="card-box mb-4">
      <div class="card-h">
        <h2>Informasi Dokumen</h2>
      </div>
      <div class="p-4">
        <div class="mb-3">
          <small class="text-uppercase fw-semibold d-block text-muted" style="font-size: 11px;">Perihal</small>
          <div class="fw-bold" style="font-size: 15px; color: var(--ink);">{{ $surat->perihal }}</div>
        </div>

        <div class="mb-3">
          <small class="text-uppercase fw-semibold d-block text-muted" style="font-size: 11px;">Tanggal Surat</small>
          <div class="fw-medium" style="color: var(--ink);"><i class="bi bi-calendar3 me-1 text-primary"></i>{{ $surat->tanggal_surat->translatedFormat('d F Y') }}</div>
        </div>

        <div class="mb-3">
          <small class="text-uppercase fw-semibold d-block text-muted" style="font-size: 11px;">Pengirim / Instansi Asal</small>
          <div class="fw-medium" style="color: var(--ink);"><i class="bi bi-building me-1 text-primary"></i>{{ $surat->pengirim }}</div>
        </div>

        <div class="mb-3">
          <small class="text-uppercase fw-semibold d-block text-muted" style="font-size: 11px;">Nama File &amp; Ukuran</small>
          <div class="d-flex align-items-center gap-2 mt-1">
            <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
            <span class="small fw-semibold text-truncate" style="max-width: 180px;">{{ $surat->file_name ?? 'Dokumen.pdf' }}</span>
            <span class="chip n" style="font-size: 11px;">{{ $surat->formatted_file_size }}</span>
          </div>
        </div>

        <div class="mb-3">
          <small class="text-uppercase fw-semibold d-block text-muted" style="font-size: 11px;">Diunggah Oleh</small>
          <div class="small fw-semibold mt-1" style="color: var(--ink);">
            <i class="bi bi-person-circle me-1 text-secondary"></i> {{ $surat->uploader ? $surat->uploader->name : 'Sistem' }}
          </div>
        </div>

        @if($surat->keterangan)
          <div class="p-3 bg-light rounded-3 border mt-3">
            <small class="text-uppercase fw-semibold d-block text-muted mb-1" style="font-size: 10.5px;">Catatan Khusus</small>
            <div class="small" style="color: var(--muted); white-space: pre-line;">{{ $surat->keterangan }}</div>
          </div>
        @endif
      </div>
    </div>

    <!-- Card Riwayat Audit Pembacaan Surat -->
    <div class="card-box">
      <div class="card-h">
        <h2>Jejak Audit Dokumen</h2>
        <span class="chip n" style="font-size: 11px;">{{ $surat->activityLogs->count() }} Aktivitas</span>
      </div>
      <div style="max-height: 380px; overflow-y: auto;">
        <ul class="log">
          @forelse($surat->activityLogs as $log)
            <li>
              @if($log->action === 'upload')
                <span class="dot up"><svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5"/></svg></span>
              @elseif($log->action === 'preview')
                <span class="dot view-dot"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              @elseif($log->action === 'download')
                <span class="dot"><svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg></span>
              @else
                <span class="dot"><svg viewBox="0 0 24 24"><path d="M3 12h4l3-8 4 16 3-8h4"/></svg></span>
              @endif
              <div>
                <b>{{ $log->user ? $log->user->name : 'Sistem' }}</b>
                <p>{{ $log->description }}</p>
                <time>{{ $log->created_at->diffForHumans() }} ({{ $log->created_at->format('H:i') }})</time>
              </div>
            </li>
          @empty
            <li style="color: var(--muted); padding: 24px 0; justify-content: center;">
              Belum ada catatan aktivitas untuk surat ini.
            </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  <!-- Kolom Kanan: Penayangan PDF Mirroring Langsung (col-lg-8) -->
  <div class="col-lg-8">
    <div class="card-box h-100" id="viewerCard">
      <div class="card-h" style="border-bottom: 1px solid var(--line);">
        <div class="d-flex align-items-center gap-2">
          <span class="chip n" style="background: #fee2e2; color: #b91c1c; font-weight: 700;">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
          </span>
          <h2>Penayangan Salinan Digital (Mirroring)</h2>
        </div>
        <a href="{{ route('surat.stream', $surat) }}" target="_blank" class="btn-custom-outline" style="height: 34px; padding: 0 12px; font-size: 12.5px;" title="Buka Dokumen di Jendela Baru">
          <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
          Jendela Baru
        </a>
      </div>
      <div class="p-0 position-relative bg-light d-flex flex-column" style="min-height: 780px;">
        <!-- Embedded Secure PDF Stream Viewer -->
        <iframe src="{{ route('surat.stream', $surat) }}#toolbar=1&navpanes=0&scrollbar=1" 
                id="pdfIframe"
                class="w-100 flex-grow-1"
                style="min-height: 780px; border: none;"
                title="Salinan Dokumen {{ $surat->nomor_surat }}">
        </iframe>

        <!-- Fallback notice jika browser memblokir embed iframe PDF -->
        <div class="p-3 text-center bg-white border-top small text-muted">
          <span>Jika pratinjau dokumen tidak tampil secara otomatis di peramban Anda, </span>
          <a href="{{ route('surat.stream', $surat) }}" target="_blank" class="fw-semibold text-primary">klik di sini untuk membuka PDF secara langsung</a>
          <span> atau </span>
          <a href="{{ route('surat.download', $surat) }}" class="fw-semibold text-success">unduh berkas PDF</a>.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleFullscreenViewer() {
    const viewer = document.getElementById('viewerCard');
    if (!document.fullscreenElement) {
      if (viewer.requestFullscreen) {
        viewer.requestFullscreen();
      } else if (viewer.webkitRequestFullscreen) {
        viewer.webkitRequestFullscreen();
      } else if (viewer.msRequestFullscreen) {
        viewer.msRequestFullscreen();
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      }
    }
  }
</script>
@endsection
