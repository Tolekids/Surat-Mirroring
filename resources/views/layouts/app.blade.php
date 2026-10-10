<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Dashboard · Mirroring Surat')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Bootstrap 5 CSS for utility & components in subpages -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  :root {
    --blue:#1d5cff; --blue-dark:#1448cc; --blue-soft:#eef3ff; --blue-mid:#cfe0ff;
    --ink:#0f1b3d; --muted:#6b7691; --line:#e8ecf4; --bg:#f6f8fc; --white:#fff; --green:#12a36b;
    --font:"Plus Jakarta Sans",system-ui,-apple-system,"Segoe UI",sans-serif;
  }
  *{box-sizing:border-box;margin:0}
  body{font-family:var(--font);color:var(--ink);background:var(--bg);display:grid;grid-template-columns:240px minmax(0, 1fr);min-height:100vh;font-size:14px;overflow-x:hidden}
  svg{width:18px;height:18px;flex:none;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
  a{color:inherit;text-decoration:none}
  :focus-visible{outline:3px solid rgba(29,92,255,.35);outline-offset:2px}

  /* sidebar */
  .side{background:var(--white);border-right:1px solid var(--line);padding:24px 16px;display:flex;flex-direction:column;position:sticky;top:0;height:100vh;z-index:100}
  .logo{display:flex;align-items:center;gap:10px;padding:0 8px;margin-bottom:30px}
  .logo-mark{width:36px;height:36px;border-radius:10px;background:var(--blue);color:#fff;display:grid;place-items:center}
  .logo b{font-size:16px;font-weight:800;letter-spacing:-.01em;display:block;line-height:1.2}
  .logo small{font-size:11px;color:var(--muted)}
  nav{display:flex;flex-direction:column;gap:4px}
  nav a{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;color:var(--muted);font-weight:500;transition:background .15s,color .15s;text-decoration:none}
  nav a:hover{background:var(--bg);color:var(--ink)}
  nav a.on{background:var(--blue-soft);color:var(--blue);font-weight:600}
  .user{margin-top:auto;display:flex;align-items:center;gap:10px;padding:12px;border:1px solid var(--line);border-radius:12px;background:var(--white)}
  .avatar{width:36px;height:36px;border-radius:50%;background:var(--blue-soft);color:var(--blue);display:grid;place-items:center;font-weight:700;font-size:13px;flex:none}
  .user div{flex:1;min-width:0}
  .user b{display:block;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .user small{font-size:11px;color:var(--blue);font-weight:600}
  .user a{color:var(--muted);padding:6px;border-radius:8px;display:grid;background:transparent;border:none;cursor:pointer}
  .user a:hover{background:var(--bg);color:var(--ink)}

  /* main */
  main{padding:32px 36px 0;min-width:0;max-width:100%;display:flex;flex-direction:column;overflow-x:hidden}
  .head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px}
  .head h1{font-size:26px;font-weight:800;letter-spacing:-.02em}
  .head p{color:var(--muted);margin-top:4px}
  .btn-custom-blue{display:inline-flex;align-items:center;gap:8px;height:42px;padding:0 18px;border-radius:11px;font:inherit;font-weight:600;font-size:14px;border:0;cursor:pointer;color:#fff;background:var(--blue);box-shadow:0 8px 16px -8px rgba(29,92,255,.7);transition:transform .12s,background .15s;text-decoration:none;white-space:nowrap}
  .btn-custom-blue:hover{background:var(--blue-dark);color:#fff;transform:translateY(-1px)}
  .btn-custom-outline{display:inline-flex;align-items:center;gap:7px;height:42px;padding:0 16px;border-radius:11px;font:inherit;font-weight:600;font-size:13.5px;color:var(--ink);border:1px solid var(--line);background:#fff;transition:background .15s,border-color .15s;text-decoration:none;cursor:pointer;white-space:nowrap}
  .btn-custom-outline:hover{background:var(--bg);color:var(--ink);border-color:#cbd5e1}
  .btn-custom-danger{display:inline-flex;align-items:center;gap:7px;height:42px;padding:0 16px;border-radius:11px;font:inherit;font-weight:600;font-size:13.5px;color:#dc2626;border:1px solid #fecaca;background:#fff;transition:background .15s,border-color .15s;text-decoration:none;cursor:pointer;white-space:nowrap}
  .btn-custom-danger:hover{background:#fee2e2;color:#b91c1c;border-color:#fca5a5}
  .btn-icon{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:9px;border:1px solid var(--line);background:#fff;color:var(--muted);transition:background .15s,color .15s,border-color .15s;text-decoration:none;cursor:pointer;padding:0}
  .btn-icon:hover{background:var(--bg);color:var(--ink);border-color:#cbd5e1}
  .btn-icon.danger:hover{background:#fee2e2;color:#dc2626;border-color:#fca5a5}
  .btn-icon.warning:hover{background:#fef3c7;color:#d97706;border-color:#fde68a}

  .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px}
  .stat{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:20px;transition:transform .15s,box-shadow .15s}
  .stat:hover{transform:translateY(-3px);box-shadow:0 14px 28px -14px rgba(29,92,255,.3)}
  .stat-top{display:flex;justify-content:space-between;align-items:center;color:var(--muted);font-weight:500}
  .ico{width:36px;height:36px;border-radius:10px;background:var(--blue-soft);color:var(--blue);display:grid;place-items:center}
  .stat strong{display:block;font-size:34px;font-weight:800;letter-spacing:-.02em;margin:14px 0 2px}
  .stat small{color:var(--muted);font-size:12.5px}

  .grid{display:grid;grid-template-columns:minmax(0, 1fr) 320px;gap:16px;align-items:start}
  .card-box{background:var(--white);border:1px solid var(--line);border-radius:16px;overflow:hidden;min-width:0}
  .card-h{display:flex;justify-content:space-between;align-items:center;padding:18px 22px}
  .card-h h2{font-size:15px;font-weight:700;margin:0}
  .link{color:var(--blue);font-weight:600;font-size:13px;display:inline-flex;align-items:center;gap:6px;text-decoration:none}
  .link:hover{text-decoration:underline}

  .table-wrap{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}
  table.dashboard-table{width:100%;border-collapse:collapse}
  table.dashboard-table th{text-align:left;font-size:12px;font-weight:600;color:var(--muted);padding:10px 14px;background:var(--bg);border:none;white-space:nowrap}
  table.dashboard-table th:first-child, table.dashboard-table td:first-child{padding-left:22px}
  table.dashboard-table th:last-child, table.dashboard-table td:last-child{text-align:right;padding-right:22px;white-space:nowrap}
  table.dashboard-table td{padding:12px 14px;border-top:1px solid var(--line);vertical-align:middle}
  table.dashboard-table td b{font-weight:600;display:block}
  table.dashboard-table td small{color:var(--muted);font-size:12.5px}
  .sub{max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .chip{display:inline-block;padding:4px 10px;border-radius:99px;font-size:12px;font-weight:600;background:var(--blue-soft);color:var(--blue);white-space:nowrap}
  .chip.g{background:#e6f7f0;color:var(--green)}
  .chip.n{background:#f1f3f8;color:#4a5575}
  .view{display:inline-flex;align-items:center;gap:6px;height:34px;padding:0 14px;border-radius:9px;font-weight:600;font-size:13px;color:var(--blue);border:1px solid var(--blue-mid);background:#fff;transition:background .15s;text-decoration:none;white-space:nowrap}
  .view:hover{background:var(--blue);color:#fff;border-color:var(--blue)}

  .log{padding:2px 22px 14px;list-style:none;margin:0}
  .log li{display:flex;gap:12px;padding:14px 0;border-top:1px solid var(--line)}
  .log li:first-child{border-top:0}
  .dot{width:30px;height:30px;border-radius:50%;background:var(--bg);color:var(--muted);display:grid;place-items:center;flex:none}
  .dot svg{width:14px;height:14px}
  .dot.up{background:#e6f7f0;color:var(--green)}
  .dot.view-dot{background:var(--blue-soft);color:var(--blue)}
  .log b{font-weight:600;display:block;font-size:13px}
  .log p{color:var(--muted);font-size:12.5px;line-height:1.45;margin-top:2px}
  .log time{font-size:11.5px;color:#9aa4bb;display:block;margin-top:4px}

  footer.dash-footer{margin-top:auto;padding:28px 0;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;color:var(--muted);font-size:12.5px;background:transparent;border:none}
  footer.dash-footer .ok{display:inline-flex;align-items:center;gap:6px;color:var(--green);font-weight:600}
  footer.dash-footer .ok::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--green)}

  @media(min-width:1400px){.sub{max-width:280px}}
  @media(max-width:1200px){.grid{grid-template-columns:1fr}.stats{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:860px){
    body{grid-template-columns:1fr}
    .side{position:static;height:auto;flex-direction:row;align-items:center;gap:12px;padding:12px 16px;border-right:0;border-bottom:1px solid var(--line);overflow-x:auto}
    .logo{margin:0}.logo small,.user div,nav a span{display:none}
    nav{flex-direction:row}.user{margin:0 0 0 auto;padding:6px;border:0}
    main{padding:22px 16px 0}
    table.dashboard-table th:nth-child(3),table.dashboard-table td:nth-child(3){display:none}
  }
  @media(max-width:520px){.stats{grid-template-columns:1fr 1fr}.stat{padding:16px}.stat strong{font-size:28px}}
  @media(prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
@stack('styles')
</head>
<body>
  @php
    $words = explode(' ', auth()->user()->name ?? 'User');
    $initials = '';
    foreach (array_slice($words, 0, 2) as $w) {
        $initials .= strtoupper(substr($w, 0, 1));
    }
    $initials = $initials ?: 'U';
  @endphp

  <aside class="side">
    <div class="logo">
      <div class="logo-mark">
        <svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
      </div>
      <div><b>Mirroring Surat</b><small>MVP v1.0</small></div>
    </div>
    <nav>
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'on' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="{{ route('surat.index') }}" class="{{ request()->routeIs('surat.index') ? 'on' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        <span>Daftar Surat</span>
      </a>
      @if(auth()->user()->isStaf() || auth()->user()->isAdmin())
        <a href="{{ route('surat.create') }}" class="{{ request()->routeIs('surat.create') ? 'on' : '' }}">
          <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
          <span>Upload Surat</span>
        </a>
      @endif
      @if(auth()->user()->isAtasan() || auth()->user()->isAdmin())
        <a href="{{ route('activity_logs.index') }}" class="{{ request()->routeIs('activity_logs.*') ? 'on' : '' }}">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
          <span>Riwayat Aktivitas</span>
        </a>
      @endif
      @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'on' : '' }}">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5a6 6 0 0 1 3.5 5.5"/></svg>
          <span>Kelola Pengguna</span>
        </a>
      @endif
    </nav>
    <div class="user">
      <div class="avatar">{{ $initials }}</div>
      <div>
        <b title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</b>
        <small>{{ ucfirst(auth()->user()->role) }}</small>
      </div>
      <form action="{{ route('logout') }}" method="POST" id="logoutFormApp" style="display:none;">
        @csrf
      </form>
      <button type="submit" form="logoutFormApp" title="Keluar dari sistem" aria-label="Keluar" 
              style="background:none; border:none; padding:6px; border-radius:8px; cursor:pointer; color:var(--muted); display:grid; place-items:center;">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      </button>
    </div>
  </aside>

  <main>
    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @yield('content')

    <footer class="dash-footer">
      <span><b>Aplikasi Mirroring Surat</b> &copy; {{ date('Y') }} &mdash; Media penayangan &amp; salinan digital surat dinas.</span>
      <span class="ok">Keamanan dokumen aktif</span>
    </footer>
  </main>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
