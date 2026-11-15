<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BiteUp')</title>
    <style>
        :root { --green-900:#0f3d2a; --green-700:#1b5e3f; --green-600:#2e7d32; --green-500:#388e3c; --bg:#f3f6f4; }
        * { box-sizing:border-box; margin:0; padding:0; font-family:'Segoe UI',Arial,sans-serif; }
        body { background:var(--bg); display:flex; min-height:100vh; color:#1f2937; }

        .sidebar { width:230px; background:var(--green-900); color:#fff; padding:20px 14px; flex-shrink:0; }
        .brand { font-size:20px; font-weight:700; margin-bottom:28px; padding-left:8px; }
        .menu a { display:block; padding:11px 14px; margin-bottom:6px; color:#cfe5d8;
                  text-decoration:none; border-radius:8px; font-size:14px; }
        .menu a:hover { background:var(--green-700); color:#fff; }
        .menu a.active { background:var(--green-600); color:#fff; font-weight:600; }

        .main { flex:1; display:flex; flex-direction:column; min-width:0; }
        .topbar { background:#fff; padding:14px 28px; display:flex; justify-content:space-between;
                  align-items:center; box-shadow:0 1px 3px rgba(0,0,0,.06); }
        .topbar .user { font-size:14px; font-weight:600; color:var(--green-700); }
        .content { padding:28px; }

        h1 { font-size:22px; }
        .breadcrumb { font-size:13px; color:#6b7280; margin:4px 0 20px; }
        .card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 4px rgba(0,0,0,.06); }
        .toolbar { display:flex; justify-content:space-between; gap:12px; margin-bottom:16px; }
        .toolbar input { flex:1; max-width:380px; padding:10px 14px; border:1px solid #d1d5db;
                         border-radius:8px; font-size:14px; }
        .btn { background:var(--green-600); color:#fff; padding:10px 18px; border:0; border-radius:8px;
               font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; }
        .btn:hover { background:var(--green-500); }

        table { width:100%; border-collapse:collapse; font-size:14px; }
        th { text-align:left; background:#f1f5f2; padding:12px; color:#374151; }
        td { padding:12px; border-top:1px solid #eef0ee; }
        tr:hover td { background:#fafcfb; }
        .empty { text-align:center; padding:30px; color:#6b7280; }
        .pagination { margin-top:16px; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">🌿 BiteUp</div>
        <nav class="menu">
            <a href="#">Dashboard</a>
            <a href="#">Wilayah</a>
            <a href="#">Indikator</a>
            <a href="{{ route('kelompok-sasaran.index') }}"
               class="{{ request()->routeIs('kelompok-sasaran.*') ? 'active' : '' }}">Kelompok Sasaran</a>
            <a href="#">Program</a>
            <a href="#">Target Program</a>
            <a href="#">Laporan</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <span></span>
            <span class="user">{{ auth()->user()->name ?? 'admin' }}</span>
        </header>
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>