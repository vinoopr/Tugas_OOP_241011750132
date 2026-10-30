<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - MyProfile</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}" defer></script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary shadow-sm" aria-label="Navigasi utama">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand fw-bold" href="{{ route('admin.home') }}">MyProfile</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto align-items-sm-center gap-sm-3">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid d-flex flex-column flex-grow-1">
        <div class="row flex-grow-1">
            <aside class="col-12 col-md-2 bg-dark text-white p-3">
                <h2 class="h5 border-bottom border-secondary pb-3 mb-3">Admin Menu</h2>
                <nav class="nav nav-pills flex-column gap-2" aria-label="Menu admin">
                    <a href="{{ route('admin.home') }}" class="nav-link text-white {{ request()->routeIs('admin.home') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.projects.index') }}" class="nav-link text-white {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">Data Project</a>
                </nav>
            </aside>
            <div class="col-12 col-md-10 d-flex flex-column bg-light p-3 p-lg-4">
                <main class="flex-grow-1">
                    @yield('content')
                </main>
                <footer class="text-center text-muted border-top pt-3 mt-4">
                    <small>&copy; {{ date('Y') }} MyProfile. All rights reserved.</small>
                </footer>
            </div>
        </div>
    </div>
</body>
</html>
