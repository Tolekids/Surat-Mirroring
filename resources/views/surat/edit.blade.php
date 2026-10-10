@extends('layouts.app')

@section('title', 'Edit Surat - ' . $surat->nomor_surat . ' · Mirroring Surat')

@section('content')
<div class="head">
  <div>
    <h1>Perbarui Data Surat</h1>
    <p>Ubah rincian metadata atau ganti berkas PDF salinan surat.</p>
  </div>
  <div>
    <a href="{{ route('surat.show', $surat) }}" class="btn-custom-outline">
      <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Kembali ke Tampilan Surat
    </a>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-10 col-xl-9">
    <div class="card-box p-4 p-md-5 mb-5">
      <form action="{{ route('surat.update', $surat) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="nomor_surat" class="form-label fw-semibold small text-secondary">
              Nomor Surat <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" 
                   id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required>
            @error('nomor_surat')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="tanggal_surat" class="form-label fw-semibold small text-secondary">
              Tanggal Surat <span class="text-danger">*</span>
            </label>
            <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" 
                   id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat->format('Y-m-d')) }}" required>
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
                 id="perihal" name="perihal" value="{{ old('perihal', $surat->perihal) }}" required>
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
                   id="pengirim" name="pengirim" value="{{ old('pengirim', $surat->pengirim) }}" required>
            @error('pengirim')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="kategori" class="form-label fw-semibold small text-secondary">
              Kategori Surat <span class="text-danger">*</span>
            </label>
            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
              @foreach($kategoriList as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $surat->kategori) === $kat ? 'selected' : '' }}>
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
            Ganti Berkas PDF (Opsional)
          </label>
          <div class="p-4 border rounded-3 bg-light">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-file-earmark-pdf-fill text-danger fs-3 me-2"></i>
              <div>
                <span class="small fw-semibold text-dark d-block">Berkas saat ini: {{ $surat->file_name ?? 'Dokumen.pdf' }}</span>
                <span class="chip n" style="font-size: 11px;">Ukuran: {{ $surat->formatted_file_size }}</span>
              </div>
            </div>
            <input type="file" class="form-control @error('file_surat') is-invalid @enderror mt-2" 
                   id="file_surat" name="file_surat" accept="application/pdf">
            <small class="text-muted mt-1 d-block">
              Kosongkan jika Anda tidak ingin mengganti file PDF lama. Maksimal 10 MB.
            </small>
            @error('file_surat')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="keterangan" class="form-label fw-semibold small text-secondary">
            Catatan / Keterangan Tambahan
          </label>
          <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                    id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $surat->keterangan) }}</textarea>
          @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-4">
          <a href="{{ route('surat.show', $surat) }}" class="btn-custom-outline">
            Batal
          </a>
          <button type="submit" class="btn-custom-blue">
            <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
