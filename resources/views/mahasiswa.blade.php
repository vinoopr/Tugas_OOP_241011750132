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

    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1">UNPAM - Profile Mahasiswa</span>
        </div>
    </nav>

    <main class="container flex-grow-1 d-flex justify-content-center align-items-center py-5">
        <div class="card shadow-sm border-0 w-100" style="max-width: 650px;">
            <div class="card-header bg-white text-center py-5">
                <img
                    src="{{ asset('foto.png') }}"
                    alt="Foto Mahasiswa"
                    class="rounded-circle img-thumbnail mb-4"
                    style="width: 150px; height: 150px; object-fit: cover;"
                >

                <h2 class="fw-bold">Profile Mahasiswa</h2>
                <span class="badge bg-success px-3 py-2 mt-3">
                    {{ $mahasiswa['status'] }}
                </span>
            </div>

            <div class="card-body text-center py-4 fs-5">
                <p><strong>Nama:</strong> {{ $mahasiswa['nama'] }}</p>
                <p><strong>NIM:</strong> {{ $mahasiswa['nim'] }}</p>
                <p><strong>Prodi:</strong> {{ $mahasiswa['prodi'] }}</p>
                <p><strong>Email:</strong> {{ $mahasiswa['email'] }}</p>
                <p class="mb-0"><strong>Kampus:</strong> {{ $mahasiswa['kampus'] }}</p>
            </div>
        </div>
    </main>

    <footer class="bg-white border-top text-center py-4 mt-auto">
        © {{ date('Y') }} UNPAM. All rights reserved.
    </footer>

</body>
</html>
