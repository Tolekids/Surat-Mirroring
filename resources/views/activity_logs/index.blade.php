@extends('layouts.app')

@section('title', 'Riwayat Aktivitas & Audit Trail · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <h1>Riwayat Aktivitas &amp; Audit Trail</h1>
    <p>Catatan pemantauan akses sistem, pengunggahan surat, pembacaan surat oleh atasan, dan perubahan data.</p>
  </div>
</div>

<!-- Filter Log -->
<div class="card-box mb-4 p-3 p-md-4">
  <form method="GET" action="{{ route('activity_logs.index') }}" class="row g-3 align-items-end">
    <div class="col-lg-4 col-md-6">
      <label class="form-label small fw-semibold text-muted mb-1">Cari Deskripsi / IP</label>
      <div class="input-group">
        <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
        <input type="text" name="search" class="form-control border-start-0" 
               placeholder="Cari kata kunci deskripsi atau IP..." 
               value="{{ request('search') }}">
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-6">
      <label class="form-label small fw-semibold text-muted mb-1">Jenis Aktivitas</label>
      <select name="action" class="form-select">
        <option value="">Semua Aktivitas</option>
        @foreach($actions as $act)
          <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
            {{ ucfirst($act) }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-lg-3 col-md-6 col-6">
      <label class="form-label small fw-semibold text-muted mb-1">Pengguna</label>
      <select name="user_id" class="form-select">
        <option value="">Semua Pengguna</option>
        @foreach($users as $u)
          <option value="{{ $u->id }}" {{ (string) request('user_id') === (string) $u->id ? 'selected' : '' }}>
            {{ $u->name }} ({{ $u->role }})
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-lg-2 col-md-6 d-flex gap-2">
      <button type="submit" class="btn-custom-blue flex-grow-1" style="height: 40px;">
        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filter
      </button>
      @if(request()->hasAny(['search', 'action', 'user_id']))
        <a href="{{ route('activity_logs.index') }}" class="btn-custom-outline" style="height: 40px; padding: 0 12px;" title="Reset Filter">
          <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </a>
      @endif
    </div>
  </form>
</div>

<!-- Tabel Log -->
<div class="card-box mb-4">
  <div class="table-wrap">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th style="width: 170px;">Waktu &amp; Tanggal</th>
          <th style="width: 200px;">Pengguna</th>
          <th style="width: 120px;">Aksi</th>
          <th>Keterangan Aktivitas</th>
          <th style="width: 140px; text-align: right;">Alamat IP</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td>
              <b>{{ $log->created_at->format('d/m/Y H:i:s') }}</b>
              <small>{{ $log->created_at->diffForHumans() }}</small>
            </td>
            <td>
              @if($log->user)
                <b>{{ $log->user->name }}</b>
                <span class="chip" style="font-size: 11px; padding: 2px 8px;">
                  {{ $log->user->role }}
                </span>
              @else
                <span style="color: var(--muted); font-style: italic;">Sistem / Tamu</span>
              @endif
            </td>
            <td>
              @if($log->action === 'upload')
                <span class="chip g"><i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload</span>
              @elseif($log->action === 'preview')
                <span class="chip"><i class="bi bi-eye-fill me-1"></i> Baca/View</span>
              @elseif($log->action === 'download')
                <span class="chip" style="background: #e0f2fe; color: #0369a1;"><i class="bi bi-download me-1"></i> Unduh</span>
              @elseif($log->action === 'update')
                <span class="chip n" style="background: #fef3c7; color: #92400e;"><i class="bi bi-pencil-fill me-1"></i> Edit</span>
              @elseif($log->action === 'delete')
                <span class="chip n" style="background: #fee2e2; color: #b91c1c;"><i class="bi bi-trash-fill me-1"></i> Hapus</span>
              @else
                <span class="chip n">{{ $log->action }}</span>
              @endif
            </td>
            <td>
              <div style="font-weight: 500; color: var(--ink);">{{ $log->description }}</div>
              @if($log->surat)
                <a href="{{ route('surat.show', $log->surat) }}" class="link" style="font-size: 12px; margin-top: 3px;">
                  <i class="bi bi-link-45deg"></i> Buka Surat: {{ $log->surat->nomor_surat }}
                </a>
              @endif
            </td>
            <td style="text-align: right;">
              <span class="badge bg-light text-secondary border font-monospace">{{ $log->ip_address ?: '-' }}</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; padding: 48px 20px; color: var(--muted);">
              <i class="bi bi-clipboard-x fs-1 d-block mb-2 text-secondary"></i>
              Tidak ada log aktivitas yang ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($logs->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
      <small class="text-muted">
        Menampilkan {{ $logs->firstItem() }} sampai {{ $logs->lastItem() }} dari {{ $logs->total() }} log aktivitas
      </small>
      <div>
        {{ $logs->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
@endsection
