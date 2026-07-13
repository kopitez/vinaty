<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vinaty Inventory System')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Admin Stylesheet -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar" class="d-flex flex-column flex-shrink-0">
            <div class="sidebar-header d-flex align-items-center justify-content-between">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-boxes-stacked" style="font-size: 14px;"></i>
                        </div>
                        <span class="fw-bold text-dark text-uppercase" style="font-family: var(--font-outfit); letter-spacing: 0.3px; font-size: 12.5px; line-height: 1.2;">Vinaty Culinary's Inventory</span>
                    </div>
                </a>
                <button class="btn btn-sm d-md-none text-dark" id="sidebarCloseToggle">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <ul class="nav nav-pills flex-column mb-auto py-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('produk.index') }}" class="nav-link {{ Request::is('produk*') ? 'active' : '' }}">
                        <i class="fa-solid fa-cube"></i> Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('kategori.index') }}" class="nav-link {{ Request::is('kategori*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i> Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('stok-masuk.index') }}" class="nav-link {{ Request::is('stok-masuk*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-arrow-down text-success"></i> Stok Masuk
                    </a>
                </li>
                <li>
                    <a href="{{ route('stok-keluar.index') }}" class="nav-link {{ Request::is('stok-keluar*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-arrow-up text-danger"></i> Stok Keluar
                    </a>
                </li>
                <li>
                    <a href="{{ route('monitoring.index') }}" class="nav-link {{ Request::is('monitoring*') ? 'active' : '' }}">
                        <i class="fa-solid fa-hourglass-half"></i> Monitoring FEFO
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifikasi.index') }}" class="nav-link {{ Request::is('notifikasi*') ? 'active' : '' }}">
                        <i class="fa-regular fa-bell"></i> Notifikasi
                        @if(($navbarNotificationsCount ?? 0) > 0)
                            <span class="badge rounded-circle bg-primary ms-auto d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; font-size: 10px; color: #fff;">{{ $navbarNotificationsCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.index') }}" class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> Laporan
                    </a>
                </li>
            </ul>



            <div class="p-3 border-top bg-light bg-opacity-50">
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-3 py-2 fw-semibold" style="font-family: var(--font-outfit); font-size: 14px;">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div id="content" class="d-flex flex-column">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg admin-navbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-light rounded-3 me-2 d-md-none" id="sidebarToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <div class="d-flex align-items-center">
                            <h3 class="m-0 fw-bold text-dark" style="font-family: var(--font-outfit); font-size: 26px;">
                                @yield('header-title', 'Dashboard')
                            </h3>
                            @if(Route::is('dashboard'))
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 ms-2 fw-semibold" style="font-size: 11px;">2 Baru</span>
                            @endif
                        </div>
                        <p class="text-muted small m-0" style="font-size: 13px; font-family: var(--font-inter);">
                            @yield('header-subtitle', 'Lihat semua ringkasan sistem di sini')
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Search Icon Button -->
                    <div class="navbar-action-btn d-none d-sm-flex">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 14px;"></i>
                    </div>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <div class="navbar-action-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-bell" style="font-size: 15px;"></i>
                            @if(($navbarNotificationsCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle" style="margin-top: 10px; margin-left: -10px;"></span>
                            @endif
                        </div>
                        
                        <div class="dropdown-menu dropdown-menu-end notif-dropdown mt-2 shadow border-0 rounded-4">
                            <div class="notif-dropdown-header d-flex justify-content-between align-items-center px-3 py-2.5">
                                <span>Notifikasi Terbaru</span>
                                @if(($navbarNotificationsCount ?? 0) > 0)
                                    <span class="badge bg-danger-subtle text-danger rounded-pill">{{ $navbarNotificationsCount }} Baru</span>
                                @endif
                            </div>
                            <div style="max-height: 280px; overflow-y: auto;">
                                @if(isset($navbarNotifications) && $navbarNotifications->count() > 0)
                                    @foreach($navbarNotifications as $notif)
                                        <a href="{{ route('notifikasi.index') }}" class="notif-item unread">
                                            <p class="notif-text m-0">{{ Str::limit($notif->pesan, 75) }}</p>
                                            <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="p-4 text-center text-muted">
                                        <i class="fa-regular fa-bell-slash mb-2 fs-4 d-block text-secondary"></i>
                                        <span class="small">Tidak ada notifikasi baru</span>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('notifikasi.index') }}" class="dropdown-item text-center py-2 border-top text-primary small fw-semibold">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>

                    <!-- User Profile Button -->
                    <div class="dropdown">
                        <div class="navbar-profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="navbar-profile-avatar bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="background: linear-gradient(135deg, #4f46e5, #06b6d4) !important; font-size: 13px;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block text-start">
                                <span class="d-block small text-muted" style="font-size: 10px; line-height: 1;">Welcome back</span>
                                <span class="small fw-bold text-dark" style="font-size: 13px;">{{ Auth::user()->name ?? 'Andrew Johnson' }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-secondary ms-1" style="font-size: 9px;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2">
                            <li>
                                <div class="px-3 py-2 border-bottom">
                                    <span class="d-block small text-muted">Masuk Sebagai:</span>
                                    <span class="fw-semibold">{{ Auth::user()->email ?? 'admin@vinaty.com' }}</span>
                                </div>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2" onclick="document.getElementById('logoutForm').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="container-fluid p-4 flex-grow-1 animated-fade-in">
                
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
                        @yield('breadcrumbs')
                    </ol>
                </nav>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 p-3 mb-4" role="alert" style="border-left: 5px solid #10b981 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-check text-success me-3 fs-4"></i>
                            <div>
                                <h6 class="alert-heading fw-bold m-0 text-success">Berhasil!</h6>
                                <span class="small text-secondary">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 p-3 mb-4" role="alert" style="border-left: 5px solid #ef4444 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-exclamation text-danger me-3 fs-4"></i>
                            <div>
                                <h6 class="alert-heading fw-bold m-0 text-danger">Gagal!</h6>
                                <span class="small text-secondary">{{ session('error') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 p-3 mb-4" role="alert" style="border-left: 5px solid #ef4444 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-exclamation text-danger me-3 fs-4"></i>
                            <div>
                                <h6 class="alert-heading fw-bold m-0 text-danger">Terjadi Kesalahan!</h6>
                                <ul class="m-0 mt-1 ps-3 small text-secondary">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Yielding Main Page Content -->
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white py-3 border-top text-center mt-auto text-muted small">
                <div class="container-fluid">
                    &copy; {{ date('Y') }} <strong>Vinaty Culinary</strong>. All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <!-- Modals Section -->
    @stack('modals')

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3 animate-pulse" style="width: 64px; height: 64px;">
                        <i class="fa-solid fa-triangle-exclamation fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="deleteConfirmModalLabel" style="font-family: var(--font-outfit);">Konfirmasi Hapus</h5>
                    <p class="text-muted small mb-4" id="deleteConfirmMessage">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary-custom rounded-3 px-4 py-2 fs-7" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger rounded-3 px-4 py-2 fs-7" id="btnConfirmDelete" style="font-family: var(--font-outfit); font-weight: 500;">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById("sidebar");
            const sidebarToggle = document.getElementById("sidebarToggle");
            const sidebarCloseToggle = document.getElementById("sidebarCloseToggle");
            const wrapper = document.getElementById("wrapper");

            sidebarToggle.addEventListener("click", function (e) {
                e.preventDefault();
                sidebar.classList.toggle("d-none");
            });

            if (sidebarCloseToggle) {
                sidebarCloseToggle.addEventListener("click", function () {
                    sidebar.classList.add("d-none");
                });
            }

            // Handle responsive view default settings
            function adjustSidebar() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add("d-none");
                } else {
                    sidebar.classList.remove("d-none");
                }
            }

            adjustSidebar();
            window.addEventListener('resize', adjustSidebar);

            // Global Delete Confirmation handler
            let formToSubmit = null;
            const deleteModalEl = document.getElementById('deleteConfirmModal');
            if (deleteModalEl) {
                const deleteModal = new bootstrap.Modal(deleteModalEl);
                const confirmMessage = document.getElementById('deleteConfirmMessage');
                const btnConfirmDelete = document.getElementById('btnConfirmDelete');

                document.addEventListener('submit', function (e) {
                    const form = e.target;
                    if (form.classList.contains('delete-form') && !form.dataset.confirmed) {
                        e.preventDefault();
                        formToSubmit = form;
                        
                        const msg = form.getAttribute('data-confirm') || "Apakah Anda yakin ingin menghapus data ini?";
                        confirmMessage.textContent = msg;
                        deleteModal.show();
                    }
                });

                if (btnConfirmDelete) {
                    btnConfirmDelete.addEventListener('click', function () {
                        if (formToSubmit) {
                            formToSubmit.dataset.confirmed = "true";
                            formToSubmit.submit();
                            deleteModal.hide();
                        }
                    });
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
