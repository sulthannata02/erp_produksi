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
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar" id="sidebar">
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
                <span class="nav-text">Dashboard</span>
            </a>

            @if(auth()->user()->role === 'admin')
                {{-- ─── ADMIN MENU ─── --}}
                <a href="{{ route('materials.index') }}"
                   class="nav-item {{ request()->routeIs('materials.*') ? 'active' : '' }}" id="nav-material">
                    <span class="nav-icon"><i class="ph ph-package"></i></span>
                    <span class="nav-text">Material</span>
                </a>

                <a href="{{ route('productions.index') }}"
                   class="nav-item {{ request()->routeIs('productions.*') ? 'active' : '' }}" id="nav-produksi">
                    <span class="nav-icon"><i class="ph ph-factory"></i></span>
                    <span class="nav-text">Produksi</span>
                </a>

                <a href="{{ route('monitoring.index') }}"
                   class="nav-item {{ request()->routeIs('monitoring.*') ? 'active' : '' }}" id="nav-monitoring">
                    <span class="nav-icon"><i class="ph ph-map-trifold"></i></span>
                    <span class="nav-text">Monitoring</span>
                </a>

                <a href="{{ route('laporan.index') }}"
                   class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}" id="nav-laporan">
                    <span class="nav-icon"><i class="ph ph-chart-bar"></i></span>
                    <span class="nav-text">Laporan</span>
                </a>
            @else
                {{-- ─── OPERATOR MENU ─── --}}
                <a href="{{ route('qcs.index') }}"
                   class="nav-item {{ request()->routeIs('qcs.*') ? 'active' : '' }}" id="nav-qc">
                    <span class="nav-icon"><i class="ph ph-shield-check"></i></span>
                    <span class="nav-text">QC (Quality Control)</span>
                </a>

                <a href="{{ route('packings.index') }}"
                   class="nav-item {{ request()->routeIs('packings.*') ? 'active' : '' }}" id="nav-packing">
                    <span class="nav-icon"><i class="ph ph-archive-box"></i></span>
                    <span class="nav-text">Packing</span>
                </a>

                <a href="{{ route('tracking.index') }}"
                   class="nav-item {{ request()->routeIs('tracking.*') ? 'active' : '' }}" id="nav-tracking">
                    <span class="nav-icon"><i class="ph ph-map-trifold"></i></span>
                    <span class="nav-text">Tracking</span>
                </a>
            @endif
        </nav>
    </aside>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="main-content">

        {{-- ─── TOP HEADER ─── --}}
        <header class="top-header">
            <div class="header-left" style="display:flex; align-items:center; gap:16px;">
                <button type="button" id="sidebar-toggle" class="btn-toggle" title="Toggle Sidebar">
                    <i class="ph ph-list"></i>
                </button>
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

// Sidebar Toggle Logic
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');
const mainContent = document.querySelector('.main-content');
const overlay = document.getElementById('sidebar-overlay');

if (sidebarToggle && sidebar && mainContent && overlay) {
    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    }

    sidebarToggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
        }
    });
}
</script>

@stack('scripts')
</body>
</html>
