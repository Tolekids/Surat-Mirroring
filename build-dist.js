import fs from 'node:fs';
import path from 'node:path';

// Pastikan direktori dist tersedia untuk Netlify
const distDir = path.resolve('dist');
if (!fs.existsSync(distDir)) {
  fs.mkdirSync(distDir, { recursive: true });
}

// Salin hasil build dari public/build ke dist jika ada
const buildDir = path.resolve('public', 'build');
if (fs.existsSync(buildDir)) {
  fs.cpSync(buildDir, distDir, { recursive: true });
}

// Buat index.html di dist agar Netlify memiliki halaman utama
const htmlContent = `<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mirroring Surat - Sistem Persuratan</title>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background-color: #f1f5f9; color: #1e293b; }
    .card { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); max-width: 520px; text-align: center; }
    h1 { color: #2563eb; font-size: 1.5rem; margin-top: 0; }
    p { color: #64748b; line-height: 1.6; font-size: 0.95rem; }
    .badge { display: inline-block; background: #e0f2fe; color: #0284c7; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1rem; }
  </style>
</head>
<body>
  <div class="card">
    <span class="badge">Deployment Status: Berhasil</span>
    <h1>Aplikasi Mirroring Surat</h1>
    <p>Aset frontend telah berhasil dikompilasi.</p>
    <p><small>Catatan: Aplikasi ini berbasis <strong>PHP &amp; Laravel</strong>. Untuk menjalankan fitur lengkap (autentikasi, upload dokumen, database), jalankan di server yang mendukung PHP (seperti Railway, Render, VPS, atau Localhost).</small></p>
  </div>
</body>
</html>`;

fs.writeFileSync(path.join(distDir, 'index.html'), htmlContent);
console.log('Successfully prepared dist directory for Netlify deploy.');
