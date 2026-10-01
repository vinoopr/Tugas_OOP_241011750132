<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <header>
        @include('partial.header')
    </header>
        <main>
            @yield('content')
        </main>
        @include('partial.footer')

</body>
</html>
