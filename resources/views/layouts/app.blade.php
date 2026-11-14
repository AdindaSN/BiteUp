<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BiteUp')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; background: #f3f5f4; color: #1f2937; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 220px; background: #0f4c3a; color: #fff; padding: 20px 14px; flex-shrink: 0; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 18px; margin-bottom: 28px; padding: 0 8px; }
        .logo { background: #22c55e; width: 28px; height: 28px; border-radius: 8px; display: grid; place-items: center; font-size: 15px; }
        .menu { display: block; padding: 10px 12px; border-radius: 8px; color: #d1e7dd; text-decoration: none; font-size: 14px; margin-bottom: 4px; }
        .menu:hover { background: rgba(255,255,255,.08); }
        .menu.active { background: #1c6b52; color: #fff; font-weight: 600; }

        /* Area utama */
        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .search { width: 100%; max-width: 320px; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; }
        .user { font-size: 14px; color: #4b5563; white-space: nowrap; }
        .content { padding: 24px; }

        /* Judul halaman */
        .page-title { font-size: 22px; font-weight: 700; }
        .breadcrumb { font-size: 13px; color: #6b7280; margin: 4px 0 20px; }

        /* Kartu dan form */
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .field input, .field select { width: 100%; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #fff; }
        .field input:focus, .field select:focus { outline: 2px solid #86efac; border-color: #22c55e; }
        .field small.hint { display: block; color: #6b7280; font-size: 12px; margin-top: 4px; }
        .field small.error { display: block; color: #dc2626; font-size: 12px; margin-top: 4px; }
        .actions { display: flex; gap: 10px; margin-top: 24px; }
        .btn { padding: 9px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 0; cursor: pointer; }
        .btn-primary { background: #16a34a; color: #fff; }
        .btn-primary:hover { background: #15803d; }
        .btn-light { background: #e5e7eb; color: #374151; }

        @media (max-width: 800px) {
            .sidebar { display: none; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand"><span class="logo">B</span> BiteUp</div>
        <nav>
            <a href="#" class="menu">Dashboard</a>
            <a href="{{ route('wilayah.create') }}" class="menu {{ request()->routeIs('wilayah.*') ? 'active' : '' }}">Wilayah</a>
            <a href="#" class="menu">Indikator</a>
            <a href="#" class="menu">Kelompok Sasaran</a>
            <a href="#" class="menu">Program</a>
            <a href="#" class="menu">Target Program</a>
            <a href="#" class="menu">Laporan</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <input type="text" class="search" placeholder="Cari di BiteUp...">
            <span class="user">admin</span>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>