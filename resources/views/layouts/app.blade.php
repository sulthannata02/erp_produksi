<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ERP Produksi & Packing — PT. Actmetal Indonesia">
    <title>@yield('title', 'ERP Produksi') — PT. Actmetal Indonesia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons (Phosphor Icons CDN) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @stack('styles')
</head>
<body>
<div class="layout-wrapper">


    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="main-content">

        {{-- ─── TOP HEADER ─── --}}
        <header class="top-header">
            <div class="header-left" style="display:flex; align-items:center; gap:16px;">
                <button type="button" onclick="window.history.back()" class="btn btn-secondary btn-icon" title="Kembali">
                    <i class="ph ph-arrow-left"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-icon" title="Home">
                    <i class="ph ph-house"></i>
                </a>
                <div>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    <p>@yield('page-sub', 'Sistem Informasi Produksi & Packing')</p>
                </div>
            </div>
            <div class="header-right">
                <div class="header-date">
                    <i class="ph ph-calendar"></i>
                    <span id="current-date">{{ now()->locale('id')->translatedFormat('l, d M Y') }}</span>
                </div>

                <div class="notif-btn" title="Notifikasi">
                    <i class="ph ph-bell"></i>
                    <span class="notif-badge">3</span>
                </div>

                <div class="user-menu" style="position:relative" id="user-menu-wrapper">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span>{{ auth()->user()->name }}</span>
                    <i class="ph ph-caret-down" style="font-size:12px;color:var(--text-muted)"></i>

                    {{-- Dropdown --}}
                    <div id="user-dropdown" style="display:none;position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid #E5E9F0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);min-width:160px;z-index:200;overflow:hidden">
                        <div style="padding:12px 14px;border-bottom:1px solid #E5E9F0">
                            <div style="font-size:12px;font-weight:600;color:#1A202C">{{ auth()->user()->name }}</div>
                            <div style="font-size:11px;color:#A0AEC0;margin-top:2px">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" id="btn-logout" style="width:100%;padding:10px 14px;border:none;background:none;text-align:left;font-size:13px;font-family:'Inter',sans-serif;color:#EF4444;cursor:pointer;display:flex;align-items:center;gap:8px;font-weight:500">
                                <i class="ph ph-sign-out"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ─── PAGE BODY ─── --}}
        <main class="page-content">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="ph ph-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="ph ph-warning-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Toggle user dropdown
const userMenu = document.getElementById('user-menu-wrapper');
const userDropdown = document.getElementById('user-dropdown');
if (userMenu && userDropdown) {
    userMenu.addEventListener('click', function(e) {
        userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
    });
    document.addEventListener('click', function(e) {
        if (!userMenu.contains(e.target)) {
            userDropdown.style.display = 'none';
        }
    });
}


</script>

@stack('scripts')
</body>
</html>
