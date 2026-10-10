@extends('layouts.app')

@section('title', 'Daftar Surat · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <h1>Daftar Dokumen Surat</h1>
    <p>Kelola dan telusuri seluruh arsip salinan digital surat dinas kantor.</p>
  </div>
  @if(auth()->user()->isStaf() || auth()->user()->isAdmin())
    <a href="{{ route('surat.create') }}" class="btn-custom-blue">
      <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
      Upload salinan surat
    </a>
  @endif
</div>

<!-- Filter dan Pencarian -->
<div class="card-box mb-4 p-3 p-md-4">
  <form method="GET" action="{{ route('surat.index') }}" class="row g-3 align-items-end">
    <div class="col-lg-4 col-md-6">
      <label class="form-label small fw-semibold text-muted mb-1">Cari Surat</label>
      <div class="input-group">
        <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
        <input type="text" name="search" class="form-control border-start-0" 
               placeholder="Nomor surat, perihal, pengirim..." 
               value="{{ request('search') }}">
      </div>
    </div>

    <div class="col-lg-2 col-md-3 col-6">
      <label class="form-label small fw-semibold text-muted mb-1">Kategori</label>
      <select name="kategori" class="form-select">
        <option value="">Semua Kategori</option>
        @foreach($kategoriList as $kat)
          <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>
            {{ $kat }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-lg-2 col-md-3 col-6">
      <label class="form-label small fw-semibold text-muted mb-1">Dari Tanggal</label>
      <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
    </div>

    <div class="col-lg-2 col-md-6 col-6">
      <label class="form-label small fw-semibold text-muted mb-1">Sampai Tanggal</label>
      <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
    </div>

    <div class="col-lg-2 col-md-6 col-6 d-flex gap-2">
      <button type="submit" class="btn-custom-blue flex-grow-1" style="height: 40px;">
        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filter
      </button>
      @if(request()->hasAny(['search', 'kategori', 'dari_tanggal', 'sampai_tanggal']))
        <a href="{{ route('surat.index') }}" class="btn-custom-outline" style="height: 40px; padding: 0 12px;" title="Reset Filter">
          <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </a>
      @endif
    </div>
  </form>
</div>

<!-- Tabel Surat -->
<div class="card-box mb-4">
  <div class="table-wrap">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th style="width: 48px; text-align: center;">No</th>
          <th>Nomor Surat</th>
          <th>Tanggal Surat</th>
          <th>Perihal &amp; Pengirim</th>
          <th>Kategori</th>
          <th>Tanggal Upload</th>
          <th style="text-align: right; min-width: 140px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($surats as $index => $item)
          <tr>
            <td style="text-align: center; color: var(--muted); font-weight: 600;">
              {{ $surats->firstItem() + $index }}
            </td>
            <td>
              <b>{{ $item->nomor_surat }}</b>
              <small class="text-truncate d-inline-block" style="max-width: 180px;" title="{{ $item->file_name }}">
                <i class="bi bi-file-earmark-pdf text-danger me-1"></i>{{ $item->file_name ?? 'Dokumen.pdf' }}
              </small>
            </td>
            <td>
              <span style="font-weight: 500;">{{ $item->tanggal_surat->format('d/m/Y') }}</span>
            </td>
            <td>
              <b class="sub" style="max-width: 240px;" title="{{ $item->perihal }}">
                {{ $item->perihal }}
              </b>
              <small class="d-block text-truncate" style="max-width: 240px;">
                <i class="bi bi-building me-1"></i>{{ $item->pengirim }}
              </small>
            </td>
            <td>
              @if($item->kategori === 'Surat Masuk')
                <span class="chip g">Surat Masuk</span>
              @elseif(in_array($item->kategori, ['Nota Dinas', 'Surat Edaran']))
                <span class="chip n">{{ $item->kategori }}</span>
              @else
                <span class="chip">{{ $item->kategori }}</span>
              @endif
            </td>
            <td>
              <small class="d-block" style="color: var(--ink); font-weight: 500;">
                {{ $item->created_at->format('d/m/Y H:i') }}
              </small>
              <small style="color: var(--muted); font-size: 11.5px;">
                Oleh: {{ $item->uploader ? $item->uploader->name : '-' }}
              </small>
            </td>
            <td style="text-align: right; white-space: nowrap;">
              <div class="d-inline-flex gap-1 align-items-center">
                <a href="{{ route('surat.show', $item) }}" class="view" title="Lihat &amp; Baca Surat">
                  <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Lihat
                </a>
                <a href="{{ route('surat.download', $item) }}" class="btn-icon" title="Unduh Berkas PDF">
                  <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                </a>
                @if(auth()->user()->isStaf() || auth()->user()->isAdmin())
                  <a href="{{ route('surat.edit', $item) }}" class="btn-icon warning" title="Edit Metadata Surat">
                    <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </a>
                  <form action="{{ route('surat.destroy', $item) }}" method="POST" class="d-inline m-0" 
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat nomor {{ $item->nomor_surat }}? File dokumen juga akan dihapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon danger" title="Hapus Surat">
                      <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--muted);">
              <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
              <h5 class="fw-bold mb-1">Tidak ada surat ditemukan</h5>
              <p class="small mb-3">Silakan ubah kata kunci pencarian atau bersihkan filter yang aktif.</p>
              @if(auth()->user()->isStaf() || auth()->user()->isAdmin())
                <a href="{{ route('surat.create') }}" class="btn-custom-blue">
                  <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                  Upload Surat Sekarang
                </a>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($surats->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
      <small class="text-muted">
        Menampilkan {{ $surats->firstItem() }} sampai {{ $surats->lastItem() }} dari {{ $surats->total() }} surat
      </small>
      <div>
        {{ $surats->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
@endsection
