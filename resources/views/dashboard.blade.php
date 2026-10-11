@extends('layouts.app')

@section('title', 'Dashboard · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <h1>Selamat datang, {{ $user->name }}</h1>
    <p>Kelola dan pantau dokumen digital dinas dari satu tempat.</p>
  </div>
  @if($user->isStaf() || $user->isAdmin())
    <a href="{{ route('surat.create') }}" class="btn-custom-blue">
      <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
      Upload salinan surat
    </a>
  @else
    <a href="{{ route('surat.index') }}" class="btn-custom-blue">
      <svg viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
      Telusuri arsip surat
    </a>
  @endif
</div>

<section class="stats">
  <div class="stat">
    <div class="stat-top">
      Total surat
      <span class="ico">
        <svg viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
      </span>
    </div>
    <strong>{{ $stats['total'] }}</strong>
    <small>Tersimpan dalam sistem</small>
  </div>

  <div class="stat">
    <div class="stat-top">
      Surat masuk
      <span class="ico">
        <svg viewBox="0 0 24 24"><path d="M3 13l3-8h12l3 8v5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zM3 13h5l1 3h6l1-3h5"/></svg>
      </span>
    </div>
    <strong>{{ $stats['surat_masuk'] }}</strong>
    <small>Dari instansi luar / eksternal</small>
  </div>

  <div class="stat">
    <div class="stat-top">
      Surat keluar
      <span class="ico">
        <svg viewBox="0 0 24 24"><path d="M21 3L10 14M21 3l-7 18-4-7-7-4z"/></svg>
      </span>
    </div>
    <strong>{{ $stats['surat_keluar'] }}</strong>
    <small>Surat keluar dinas</small>
  </div>

  <div class="stat">
    <div class="stat-top">
      Surat minggu ini
      <span class="ico">
        <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
      </span>
    </div>
    <strong>{{ $stats['minggu_ini'] }}</strong>
    <small>Dokumen baru masuk</small>
  </div>
</section>

<div class="grid {{ !auth()->user()->isAdmin() ? 'grid-full' : '' }}">
  <section class="card-box">
    <div class="card-h">
      <h2>Surat terbaru untuk ditinjau</h2>
      <a href="{{ route('surat.index') }}" class="link">
        Lihat semua 
        <svg viewBox="0 0 24 24" style="width:15px;height:15px"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </div>
    <div class="table-wrap">
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>Nomor &amp; tanggal</th>
            <th>Perihal &amp; pengirim</th>
            <th>Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suratTerbaru as $item)
            <tr>
              <td>
                <b>{{ $item->nomor_surat }}</b>
                <small>{{ $item->tanggal_surat->format('d/m/Y') }}</small>
              </td>
              <td>
                <b class="sub" title="{{ $item->perihal }}">{{ $item->perihal }}</b>
                <small>{{ $item->pengirim }}</small>
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
                <a href="{{ route('surat.show', $item) }}" class="view">Lihat</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align: center; padding: 36px 20px; color: var(--muted);">
                Belum ada surat yang diunggah ke dalam sistem.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  @if(auth()->user()->isAdmin())
  <section class="card-box">
    <div class="card-h">
      <h2>Log aktivitas terkini</h2>
      <a href="{{ route('activity_logs.index') }}" class="link">Semua log</a>
    </div>
    <ul class="log">
      @forelse($aktivitasTerbaru as $log)
        <li>
          @if($log->action === 'upload')
            <span class="dot up" title="Upload">
              <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5"/></svg>
            </span>
          @elseif($log->action === 'preview')
            <span class="dot view-dot" title="Lihat/Baca">
              <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
          @else
            <span class="dot">
              <svg viewBox="0 0 24 24"><path d="M3 12h4l3-8 4 16 3-8h4"/></svg>
            </span>
          @endif
          <div>
            <b>{{ $log->user ? $log->user->name : 'Sistem' }}</b>
            <p>{{ $log->description }}</p>
            <time>{{ $log->created_at->diffForHumans() }}</time>
          </div>
        </li>
      @empty
        <li style="color: var(--muted); padding: 20px 0; justify-content: center;">
          Belum ada aktivitas tercatat.
        </li>
      @endforelse
    </ul>
  </section>
  @endif
</div>
@endsection
