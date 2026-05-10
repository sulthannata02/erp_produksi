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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

                <div class="notif-menu-wrapper" style="position:relative" id="notif-menu-wrapper">
                    <div class="notif-btn" id="notif-toggle" title="Notifikasi">
                        <i class="ph ph-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notif-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </div>
                    
                    {{-- Dropdown Notif --}}
                    <div id="notif-dropdown" style="display:none;position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid #E5E9F0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);width:320px;z-index:200;overflow:hidden">
                        <div style="padding:12px 14px;border-bottom:1px solid #E5E9F0;display:flex;justify-content:space-between;align-items:center">
                            <div style="font-size:13px;font-weight:600;color:#1A202C">Notifikasi</div>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.readAll') }}" method="POST" style="margin:0">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;color:var(--primary);font-size:11px;cursor:pointer;font-weight:600">Tandai sudah dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div style="max-height:300px;overflow-y:auto;">
                            @forelse(auth()->user()->unreadNotifications()->take(10)->get() as $notif)
                                <a href="{{ $notif->data['url'] ?? '#' }}" style="display:flex;gap:12px;padding:12px 14px;border-bottom:1px solid #F3F4F6;transition:all 0.2s;text-decoration:none" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
                                    <div style="width:32px;height:32px;border-radius:50%;background:{{ ($notif->data['color'] ?? '') == 'green' ? '#ECFDF5' : (($notif->data['color'] ?? '') == 'purple' ? '#F5F3FF' : (($notif->data['color'] ?? '') == 'orange' ? '#FFFBEB' : '#EFF6FF')) }};color:{{ ($notif->data['color'] ?? '') == 'green' ? '#10B981' : (($notif->data['color'] ?? '') == 'purple' ? '#7C3AED' : (($notif->data['color'] ?? '') == 'orange' ? '#F59E0B' : '#3B82F6')) }};display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
                                        <i class="{{ $notif->data['icon'] ?? 'ph-bell' }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:12px;font-weight:600;color:#1A202C;margin-bottom:2px">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                                        <div style="font-size:11px;color:#4A5568;line-height:1.4">{{ $notif->data['message'] ?? '' }}</div>
                                        <div style="font-size:10px;color:#A0AEC0;margin-top:4px">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @empty
                                <div style="padding:24px;text-align:center;color:#A0AEC0;font-size:12px">
                                    <i class="ph ph-bell-slash" style="font-size:24px;margin-bottom:8px;display:block"></i>
                                    Tidak ada notifikasi baru
                                </div>
                            @endforelse
                        </div>
                    </div>
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

// Toggle notif dropdown
const notifMenu = document.getElementById('notif-menu-wrapper');
const notifDropdown = document.getElementById('notif-dropdown');

document.addEventListener('click', function(e) {
    if (userMenu && userDropdown) {
        if (userMenu.contains(e.target)) {
            userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
        } else {
            userDropdown.style.display = 'none';
        }
    }
    
    if (notifMenu && notifDropdown) {
        if (notifMenu.contains(e.target)) {
            // Prevent toggling if they clicked the 'Tandai sudah dibaca' button form
            if(!e.target.closest('form')) {
                notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
            }
        } else {
            notifDropdown.style.display = 'none';
        }
    }
});


// SweetAlert2 Toast configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

@if(session('success'))
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
@endif

@if(session('error'))
    Toast.fire({
        icon: 'error',
        title: "{{ session('error') }}"
    });
@endif

// Global Delete Confirmation
function confirmDelete(formId, text = "Data yang dihapus tidak bisa dikembalikan!") {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>

@stack('scripts')
</body>
</html>
