@extends('layouts.app')

@section('title', 'Upload Salinan Surat · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <h1>Unggah Salinan Digital Surat</h1>
    <p>Isi metadata dan unggah berkas PDF untuk penayangan (mirroring) kepada atasan.</p>
  </div>
  <div>
    <a href="{{ route('surat.index') }}" class="btn-custom-outline">
      <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Kembali ke Daftar
    </a>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-10 col-xl-9">
    <div class="card-box p-4 p-md-5 mb-5">
      <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="nomor_surat" class="form-label fw-semibold small text-secondary">
              Nomor Surat <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" 
                   id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}" 
                   placeholder="Contoh: 005/142/Diskominfo/2026" required>
            @error('nomor_surat')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="tanggal_surat" class="form-label fw-semibold small text-secondary">
              Tanggal Surat <span class="text-danger">*</span>
            </label>
            <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" 
                   id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
            @error('tanggal_surat')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label for="perihal" class="form-label fw-semibold small text-secondary">
            Perihal Surat <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control @error('perihal') is-invalid @enderror" 
                 id="perihal" name="perihal" value="{{ old('perihal') }}" 
                 placeholder="Contoh: Undangan Rapat Koordinasi Penyusunan Anggaran" required>
          @error('perihal')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="pengirim" class="form-label fw-semibold small text-secondary">
              Asal Pengirim / Instansi <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control @error('pengirim') is-invalid @enderror" 
                   id="pengirim" name="pengirim" value="{{ old('pengirim') }}" 
                   placeholder="Contoh: Sekretariat Daerah / Kementerian PANRB" required>
            @error('pengirim')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="kategori" class="form-label fw-semibold small text-secondary">
              Kategori Surat <span class="text-danger">*</span>
            </label>
            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
              <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih Kategori...</option>
              @foreach($kategoriList as $kat)
                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                  {{ $kat }}
                </option>
              @endforeach
            </select>
            @error('kategori')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="file_surat" class="form-label fw-semibold small text-secondary">
            Berkas Salinan Surat (Format PDF) <span class="text-danger">*</span>
          </label>
          <div class="p-4 border rounded-3 bg-light text-center" style="border-style: dashed !important; border-color: var(--blue-mid) !important;">
            <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger d-block mb-2"></i>
            <input type="file" class="form-control @error('file_surat') is-invalid @enderror" 
                   id="file_surat" name="file_surat" accept="application/pdf" required>
            <div class="form-text mt-2 small text-muted">
              <i class="bi bi-shield-lock-fill me-1 text-success"></i> Berkas disimpan secara privat dan terlindungi. Maksimal ukuran: <strong>10 MB</strong>.
            </div>
            @error('file_surat')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="keterangan" class="form-label fw-semibold small text-secondary">
            Catatan / Keterangan Tambahan (Opsional)
          </label>
          <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                    id="keterangan" name="keterangan" rows="3" 
                    placeholder="Tambahkan catatan khusus terkait surat ini jika ada...">{{ old('keterangan') }}</textarea>
          @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-4">
          <a href="{{ route('surat.index') }}" class="btn-custom-outline">
            Batal
          </a>
          <button type="submit" class="btn-custom-blue">
            <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
            Simpan &amp; Publikasikan ke Atasan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
