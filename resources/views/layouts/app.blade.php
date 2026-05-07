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

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="ph ph-factory"></i>
            </div>
            <div class="brand-text">
                <h1>ERP PRODUKSI</h1>
                <p>PT. ACTMETAL INDONESIA</p>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-label">Menu Utama</span>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
                <span class="nav-icon"><i class="ph ph-squares-four"></i></span>
                Dashboard
            </a>

            @if(auth()->user()->role === 'admin')
                {{-- ─── ADMIN MENU ─── --}}
                <a href="{{ route('materials.index') }}"
                   class="nav-item {{ request()->routeIs('materials.*') ? 'active' : '' }}" id="nav-material">
                    <span class="nav-icon"><i class="ph ph-package"></i></span>
                    Material
                </a>

                <a href="{{ route('productions.index') }}"
                   class="nav-item {{ request()->routeIs('productions.*') ? 'active' : '' }}" id="nav-produksi">
                    <span class="nav-icon"><i class="ph ph-factory"></i></span>
                    Produksi
                </a>

                <a href="{{ route('laporan.index') }}"
                   class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}" id="nav-laporan">
                    <span class="nav-icon"><i class="ph ph-chart-bar"></i></span>
                    Laporan
                </a>
            @else
                {{-- ─── OPERATOR MENU ─── --}}
                <a href="{{ route('qcs.index') }}"
                   class="nav-item {{ request()->routeIs('qcs.*') ? 'active' : '' }}" id="nav-qc">
                    <span class="nav-icon"><i class="ph ph-shield-check"></i></span>
                    QC (Quality Control)
                </a>

                <a href="{{ route('packings.index') }}"
                   class="nav-item {{ request()->routeIs('packings.*') ? 'active' : '' }}" id="nav-packing">
                    <span class="nav-icon"><i class="ph ph-archive-box"></i></span>
                    Packing
                </a>

                <a href="{{ route('tracking.index') }}"
                   class="nav-item {{ request()->routeIs('tracking.*') ? 'active' : '' }}" id="nav-tracking">
                    <span class="nav-icon"><i class="ph ph-map-trifold"></i></span>
                    Tracking
                </a>
            @endif
        </nav>
    </aside>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="main-content">

        {{-- ─── TOP HEADER ─── --}}
        <header class="top-header">
            <div class="header-left">
                <h2>@yield('page-title', 'Dashboard')</h2>
                <p>@yield('page-sub', 'Sistem Informasi Produksi & Packing')</p>
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
