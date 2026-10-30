<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>
<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #343a40;
    color: #fff;
    padding-top: 20px;
}

.admin-sidebar h4 {
    margin-bottom: 20px;
}

.admin-sidebar .nav-link {
    color: #fff;
}

.admin-sidebar .nav-link:hover {
    background-color: #495057;
}

.admin-content {
    margin-left: 250px;
    padding: 30px;
}

@media (max-width: 768px) {
    .admin-sidebar {
        width: 100%;
        position: relative;
        height: auto;
    }

    .admin-content {
        margin-left: 0;
    }
}
</style>

<body>
    <div class="admin-sidebar shadow">
        <h4 class="text-center fw-bold text-white pb-3 border-bottom border-secondary">Admin Panel</h4>
        <ul class="nav flex-column mt-3">
            <li class="nav-item mb-1">
                <a href="{{ route('admin.home') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white">Projects</a>
            </li>
        </ul>
    </div>
    <div class="admin-content">
        <div
            class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom bg-white p-3 rounded shadow-sm">
            <h3 class="fw-bold mb-0">Dashboard</h3>
            <span class="text-muted fw-semibold">Halo, Administrator</span>
        </div>
        @yield('content')
    </div>
    @stack('scripts')
</body>


</html>
