<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - MyProfile</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}" defer></script>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark admin-header shadow-sm" aria-label="Navigasi utama">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand" href="{{ route('admin.home') }}">
                {{-- LOKASI LOGO: public/images/branding/myprofile-logo.png. Ganti file ini untuk mengganti logo. --}}
                {{-- Makna: P = profil/portofolio, simbol kode = project web. Warna putih diatur di public/css/admin.css. --}}
                <img src="{{ asset('images/branding/myprofile-logo.png') }}" class="admin-logo" alt="MyProfile">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Buka navigasi"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto align-items-md-center gap-md-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle border-0 bg-transparent" id="adminAccount" data-bs-toggle="dropdown" aria-expanded="false">Admin</button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminAccount">
                            {{-- Email otomatis mengikuti akun admin yang sedang login. --}}
                            <li><span class="dropdown-item-text text-muted">{{ auth()->user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="dropdown-item">Logout</button></form></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <h2>Admin Menu</h2>
            <nav class="nav flex-column gap-2" aria-label="Menu admin">
                <a href="{{ route('admin.home') }}" class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}" @if(request()->routeIs('admin.home')) aria-current="page" @endif>Dashboard</a>
                <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" @if(request()->routeIs('admin.projects.*')) aria-current="page" @endif>Data Project</a>
            </nav>
        </aside>
        <div class="admin-workspace">
            <main class="admin-content">@yield('content')</main>
            <footer class="admin-footer">&copy; {{ date('Y') }} MyProfile. All rights reserved.</footer>
        </div>
    </div>
</body>
</html>
