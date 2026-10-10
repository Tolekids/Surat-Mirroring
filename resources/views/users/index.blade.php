@extends('layouts.app')

@section('title', 'Kelola Pengguna Sistem · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <h1>Manajemen Pengguna &amp; Hak Akses</h1>
    <p>Kelola akun staf tata usaha, atasan, dan hak akses aplikasi.</p>
  </div>
  <div>
    <button type="button" class="btn-custom-blue" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M19 11v6M16 14h6"/></svg>
      Tambah Pengguna Baru
    </button>
  </div>
</div>

<!-- Filter Pengguna -->
<div class="card-box mb-4 p-3 p-md-4">
  <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
    <div class="col-md-5">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" name="search" class="form-control border-start-0" 
               placeholder="Cari berdasarkan nama atau email..." 
               value="{{ request('search') }}">
      </div>
    </div>
    <div class="col-md-3">
      <select name="role" class="form-select">
        <option value="">Semua Peran</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="atasan" {{ request('role') === 'atasan' ? 'selected' : '' }}>Atasan</option>
        <option value="staf" {{ request('role') === 'staf' ? 'selected' : '' }}>Staf</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn-custom-blue w-100" style="height: 40px;">
        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filter
      </button>
    </div>
    @if(request()->hasAny(['search', 'role']))
      <div class="col-md-2">
        <a href="{{ route('users.index') }}" class="btn-custom-outline w-100" style="height: 40px;">
          Reset
        </a>
      </div>
    @endif
  </form>
</div>

<!-- Tabel Pengguna -->
<div class="card-box mb-4">
  <div class="table-wrap">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th style="width: 48px; text-align: center;">No</th>
          <th>Nama Pengguna</th>
          <th>Alamat Email</th>
          <th>Hak Akses</th>
          <th>Status Akun</th>
          <th>Tanggal Terdaftar</th>
          <th style="text-align: right; min-width: 140px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $index => $u)
          <tr>
            <td style="text-align: center; color: var(--muted); font-weight: 600;">
              {{ $users->firstItem() + $index }}
            </td>
            <td>
              <b>{{ $u->name }}</b>
              @if($u->id === auth()->id())
                <span class="chip n" style="font-size: 11px; padding: 2px 8px;">Akun Anda</span>
              @endif
            </td>
            <td>
              <span style="color: var(--muted); font-size: 13px;">{{ $u->email }}</span>
            </td>
            <td>
              <span class="chip" style="text-transform: uppercase; font-size: 11px; padding: 3px 8px;">
                {{ $u->role }}
              </span>
            </td>
            <td>
              @if($u->is_active)
                <span class="chip g" style="font-size: 11px; padding: 3px 8px;">
                  <i class="bi bi-check-circle-fill me-1"></i> Aktif
                </span>
              @else
                <span class="chip n" style="background: #fee2e2; color: #b91c1c; font-size: 11px; padding: 3px 8px;">
                  <i class="bi bi-x-circle-fill me-1"></i> Nonaktif
                </span>
              @endif
            </td>
            <td>
              <small>{{ $u->created_at->format('d/m/Y') }}</small>
            </td>
            <td style="text-align: right; white-space: nowrap;">
              <div class="d-inline-flex gap-1 align-items-center">
                <button type="button" class="btn-icon" 
                        data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" title="Edit Pengguna">
                  <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>

                @if($u->id !== auth()->id())
                  <form action="{{ route('users.toggle_status', $u) }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn-icon {{ $u->is_active ? 'warning' : '' }}" 
                            style="{{ $u->is_active ? '' : 'color: var(--green);' }}"
                            title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                      @if($u->is_active)
                        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                      @else
                        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                      @endif
                    </button>
                  </form>

                  <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline m-0"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon danger" title="Hapus Akun">
                      <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>

          <!-- Modal Edit User -->
          <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content" style="border-radius: 16px; border: 1px solid var(--line);">
                <form action="{{ route('users.update', $u) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-header" style="border-bottom: 1px solid var(--line);">
                    <h5 class="modal-title fw-bold">Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-4">
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Nama Lengkap</label>
                      <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Email</label>
                      <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Hak Akses (Role)</label>
                      <select name="role" class="form-select" required>
                        <option value="staf" {{ $u->role === 'staf' ? 'selected' : '' }}>Staf / Operator</option>
                        <option value="atasan" {{ $u->role === 'atasan' ? 'selected' : '' }}>Atasan / Viewer</option>
                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Password Baru (Opsional)</label>
                      <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                    </div>
                  </div>
                  <div class="modal-footer" style="border-top: 1px solid var(--line);">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--muted);">
              Tidak ada data pengguna.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($users->hasPages())
    <div class="p-3 border-top">
      {{ $users->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- Modal Tambah User Baru -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 16px; border: 1px solid var(--line);">
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="border-bottom: 1px solid var(--line);">
          <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Email Login</label>
            <input type="email" name="email" class="form-control" placeholder="budi@kantor.go.id" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Hak Akses (Role)</label>
            <select name="role" class="form-select" required>
              <option value="staf" selected>Staf / Operator (Unggah &amp; Kelola Surat)</option>
              <option value="atasan">Atasan / Viewer (Pemantauan &amp; Baca Dokumen)</option>
              <option value="admin">Administrator Sistem</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Password Awal</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--line);">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> Daftarkan Pengguna
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
