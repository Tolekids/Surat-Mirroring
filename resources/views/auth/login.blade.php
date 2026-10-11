<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk · Mirroring Surat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --blue: #1d5cff; --blue-dark: #1448cc; --blue-deep: #0b2a85;
    --blue-soft: #eef3ff; --blue-mid: #cfe0ff;
    --ink: #0f1b3d; --muted: #6b7691; --line: #e3e8f2; --white: #fff;
    --font: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
  }
  * { box-sizing: border-box; margin: 0; }
  html, body { min-height: 100%; }
  body {
    font-family: var(--font); color: var(--ink);
    min-height: 100vh; overflow-x: hidden;
    background:
      radial-gradient(900px 500px at 12% 8%, #dbe7ff 0%, transparent 60%),
      radial-gradient(700px 500px at 95% 95%, #d6e4ff 0%, transparent 60%),
      #fff;
    display: grid; place-items: center; padding: 32px 20px;
    position: relative;
  }

  /* lantai grid 3D */
  .floor {
    position: fixed; left: -20%; right: -20%; bottom: -10%; height: 55%;
    perspective: 500px; pointer-events: none; z-index: 0;
    -webkit-mask-image: linear-gradient(to top, #000 10%, transparent 90%);
            mask-image: linear-gradient(to top, #000 10%, transparent 90%);
  }
  .floor::before {
    content: ""; position: absolute; inset: 0;
    transform: rotateX(62deg); transform-origin: bottom;
    background-image:
      linear-gradient(rgba(29,92,255,.22) 1px, transparent 1px),
      linear-gradient(90deg, rgba(29,92,255,.22) 1px, transparent 1px);
    background-size: 56px 56px;
  }

  .layout {
    position: relative; z-index: 1; width: 100%; max-width: 980px;
    display: grid; grid-template-columns: 1fr 400px; gap: 40px; align-items: center;
  }

  /* ===== Ilustrasi kiri ===== */
  .hero { position: relative; height: 480px; }
  .hero-text { position: absolute; top: 0; left: 0; z-index: 3; max-width: 340px; }
  .hero-text h2 { font-size: 30px; font-weight: 800; letter-spacing: -0.02em; line-height: 1.15; }
  .hero-text p { margin-top: 10px; font-size: 15px; color: var(--muted); line-height: 1.55; }

  .stage { position: absolute; inset: 70px 0 0 0; perspective: 1400px; }
  .scene {
    position: absolute; left: 50%; top: 52%; width: 0; height: 0;
    transform-style: preserve-3d;
    transform: rotateX(58deg) rotateZ(-38deg);
  }
  .sheet {
    position: absolute; width: 190px; height: 250px; margin: -125px 0 0 -95px;
    border-radius: 14px; background: #fff; border: 1px solid var(--blue-mid);
    box-shadow: 0 30px 40px -10px rgba(20,72,204,.28);
    padding: 22px 20px; transform-style: preserve-3d;
    animation: lift 6s ease-in-out infinite;
  }
  .sheet i { display: block; height: 7px; border-radius: 4px; background: var(--blue-mid); margin-bottom: 12px; }
  .sheet i:nth-child(2) { width: 80%; } .sheet i:nth-child(3) { width: 92%; }
  .sheet i:nth-child(4) { width: 60%; } .sheet i:nth-child(5) { width: 85%; }
  .sheet .tag {
    position: absolute; right: 18px; bottom: 18px; width: 38px; height: 38px;
    border-radius: 50%; background: var(--blue); display: grid; place-items: center;
  }
  .sheet .tag svg { width: 18px; height: 18px; stroke: #fff; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
  .s1 { transform: translateZ(0);    background: var(--blue-soft); animation-delay: 0s; }
  .s2 { transform: translateZ(46px);  animation-delay: .4s; }
  .s3 { transform: translateZ(92px);  animation-delay: .8s; }
  .s3 i:first-child { background: var(--blue); width: 50%; }
  @keyframes lift {
    0%,100% { translate: 0 0 0; } 50% { translate: 0 0 14px; }
  }

  /* kubus 3D */
  .cube { position: absolute; width: 56px; height: 56px; transform-style: preserve-3d; animation: spin 14s linear infinite; }
  .cube b {
    position: absolute; inset: 0; border: 1px solid rgba(255,255,255,.7);
    background: linear-gradient(135deg, rgba(29,92,255,.85), rgba(80,140,255,.55));
  }
  .cube b:nth-child(1) { transform: translateZ(28px); }
  .cube b:nth-child(2) { transform: rotateY(180deg) translateZ(28px); }
  .cube b:nth-child(3) { transform: rotateY(90deg) translateZ(28px);  background: linear-gradient(135deg, #1448cc, #4a86ff); }
  .cube b:nth-child(4) { transform: rotateY(-90deg) translateZ(28px); background: linear-gradient(135deg, #1448cc, #4a86ff); }
  .cube b:nth-child(5) { transform: rotateX(90deg) translateZ(28px);  background: linear-gradient(135deg, #8fb4ff, #cfe0ff); }
  .cube b:nth-child(6) { transform: rotateX(-90deg) translateZ(28px); background: #0b2a85; }
  .cube-wrap { position: absolute; perspective: 600px; z-index: 2; }
  .cw1 { left: 6%;  bottom: 6%; animation: bob 7s ease-in-out infinite; }
  .cw2 { right: 4%; top: 34%; animation: bob 8s ease-in-out infinite reverse; }
  .cw2 .cube { width: 38px; height: 38px; animation-duration: 10s; }
  .cw2 .cube b { transform-origin: center; }
  .cw2 .cube b:nth-child(1) { transform: translateZ(19px); }
  .cw2 .cube b:nth-child(2) { transform: rotateY(180deg) translateZ(19px); }
  .cw2 .cube b:nth-child(3) { transform: rotateY(90deg) translateZ(19px); }
  .cw2 .cube b:nth-child(4) { transform: rotateY(-90deg) translateZ(19px); }
  .cw2 .cube b:nth-child(5) { transform: rotateX(90deg) translateZ(19px); }
  .cw2 .cube b:nth-child(6) { transform: rotateX(-90deg) translateZ(19px); }
  @keyframes spin { to { transform: rotateX(360deg) rotateY(360deg); } }
  @keyframes bob { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-16px); } }

  /* ===== Kartu login ===== */
  .card-wrap { perspective: 1200px; }
  .card {
    position: relative; background: #fff; border-radius: 22px; padding: 34px 32px 28px;
    border: 1px solid var(--line);
    box-shadow:
      0 1px 0 #fff inset,
      0 2px 4px rgba(15,27,61,.04),
      0 18px 40px -12px rgba(29,92,255,.25),
      0 50px 80px -30px rgba(15,27,61,.25);
    transform-style: preserve-3d;
    transition: transform .25s ease-out;
  }
  .card::before { /* lapisan kertas di belakang kartu */
    content: ""; position: absolute; inset: 10px -10px -10px 10px; z-index: -1;
    border-radius: 22px; background: var(--blue-mid); opacity: .6;
    transform: translateZ(-20px);
  }
  .brand { display: flex; align-items: center; gap: 14px; margin-bottom: 26px; transform: translateZ(30px); }
  .brand-mark {
    width: 48px; height: 48px; border-radius: 14px; flex: none; color: #fff;
    background: linear-gradient(145deg, #4a86ff, var(--blue-dark));
    display: grid; place-items: center;
    box-shadow: 0 6px 0 var(--blue-deep), 0 14px 20px -4px rgba(20,72,204,.5);
    margin-bottom: 6px;
  }
  .brand-mark svg { width: 24px; height: 24px; }
  .brand h1 { font-size: 21px; font-weight: 800; letter-spacing: -0.02em; }
  .brand p { font-size: 12.5px; color: var(--muted); margin-top: 2px; }

  .notice { background: var(--blue-soft); color: var(--blue-dark); border-radius: 10px; padding: 10px 14px; font-size: 13.5px; margin-bottom: 20px; }
  .notice-error { background: #fee2e2; color: #b91c1c; border-radius: 10px; padding: 10px 14px; font-size: 13.5px; margin-bottom: 20px; }
  .notice-success { background: #dcfce7; color: #15803d; border-radius: 10px; padding: 10px 14px; font-size: 13.5px; margin-bottom: 20px; }

  .field { margin-bottom: 16px; }
  label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 7px; }
  input[type="email"], input[type="password"] {
    width: 100%; height: 46px; padding: 0 14px; font: inherit; font-size: 15px; color: var(--ink);
    background: #f8faff; border: 1px solid var(--line); border-radius: 11px;
    box-shadow: inset 0 2px 3px rgba(15,27,61,.05);
    transition: border-color .15s, box-shadow .15s, background .15s;
  }
  input::placeholder { color: #a3adc2; }
  input:focus { outline: none; background: #fff; border-color: var(--blue); box-shadow: 0 0 0 4px rgba(29,92,255,.13); }

  .check { display: flex; align-items: center; gap: 9px; font-size: 14px; color: var(--muted); margin: 2px 0 22px; cursor: pointer; font-weight: 400; }
  .check input { width: 17px; height: 17px; accent-color: var(--blue); margin: 0; }

  .btn {
    width: 100%; height: 50px; border: 0; border-radius: 12px; cursor: pointer;
    font: inherit; font-size: 15px; font-weight: 700; color: #fff;
    background: linear-gradient(180deg, #3b78ff, var(--blue));
    box-shadow: 0 5px 0 var(--blue-deep), 0 14px 22px -6px rgba(20,72,204,.55);
    transition: transform .1s, box-shadow .1s;
  }
  .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 0 var(--blue-deep), 0 18px 26px -6px rgba(20,72,204,.55); }
  .btn:active { transform: translateY(4px); box-shadow: 0 1px 0 var(--blue-deep), 0 4px 8px -2px rgba(20,72,204,.5); }
  .btn:focus-visible { outline: 3px solid rgba(29,92,255,.35); outline-offset: 3px; }

  @media (max-width: 900px) {
    .layout { grid-template-columns: 1fr; max-width: 420px; }
    .hero { display: none; }
  }
  @media (prefers-reduced-motion: reduce) {
    *, *::before { animation: none !important; transition: none !important; }
  }
</style>
</head>
<body>
  <div class="floor" aria-hidden="true"></div>

  <div class="layout">
    <aside class="hero" aria-hidden="true">
      <div class="hero-text">
        <h2>Setiap surat, tersalin dan terpantau.</h2>
        <p>Salinan digital dan pemantauan dokumen kantor dalam satu tempat.</p>
      </div>
      <div class="stage">
        <div class="scene">
          <div class="sheet s1"><i></i><i></i><i></i><i></i><i></i></div>
          <div class="sheet s2"><i></i><i></i><i></i><i></i><i></i></div>
          <div class="sheet s3"><i></i><i></i><i></i><i></i><i></i>
            <span class="tag"><svg viewBox="0 0 24 24"><path d="M5 12l5 5L20 7"/></svg></span>
          </div>
        </div>
      </div>
      <div class="cube-wrap cw1"><div class="cube"><b></b><b></b><b></b><b></b><b></b><b></b></div></div>
      <div class="cube-wrap cw2"><div class="cube"><b></b><b></b><b></b><b></b><b></b><b></b></div></div>
    </aside>

    <main class="card-wrap">
      <div class="card" id="card">
        <header class="brand">
          <div class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 15h6M9 18h4"/></svg>
          </div>
          <div>
            <h1>Mirroring Surat</h1>
            <p>Salinan digital &amp; pemantauan dokumen</p>
          </div>
        </header>

        @if(session('info'))
          <div class="notice" role="status">{{ session('info') }}</div>
        @endif

        @if(session('success'))
          <div class="notice-success" role="status">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="notice-error" role="alert">
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        <form action="{{ route('login') }}" method="POST" autocomplete="off">
          @csrf

          <div class="field">
            <label for="email">Alamat email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@kantor.go.id" autocomplete="off" autocapitalize="off" spellcheck="false" readonly onfocus="this.removeAttribute('readonly')" required>
          </div>

          <div class="field">
            <label for="password">Kata sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" required>
          </div>

          <label class="check">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Ingat saya
          </label>

          <button type="submit" class="btn">Masuk</button>
        </form>
      </div>
    </main>
  </div>

  <script>
    // Efek kartu miring mengikuti kursor (3D tilt)
    const card = document.getElementById("card");
    const reduce = matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (!reduce && matchMedia("(hover: hover)").matches) {
      document.addEventListener("mousemove", e => {
        const x = (e.clientX / innerWidth - .5), y = (e.clientY / innerHeight - .5);
        card.style.transform = `rotateY(${x * 7}deg) rotateX(${-y * 7}deg)`;
      });
      document.addEventListener("mouseleave", () => card.style.transform = "");
    }
  </script>

  <footer class="site-footer">
            <p>Author: RUANG SINI 2026</p>
        </footer>
</footer>
</body>
</html>