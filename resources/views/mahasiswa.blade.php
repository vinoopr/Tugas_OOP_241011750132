<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">UNPAM - Profile Mahasiswa</a>
        </div>
    </nav>
    <div class="container flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white text-center py-4">
                        <div class="d-flex justify-content-center mb-3">
                            <img src="{{ asset('assets/Kursi.jpg') }}" alt="" class="rounded-circle img-thumbnail"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="card-title mb-4">Profile Mahasiswa</h5>
                        <span class="badge bg-success">{{ $mahasiswa['status'] }}</span>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text"><strong>Nama:</strong> {{ $mahasiswa['nama'] }}</p>
                        <p class="card-text"><strong>NIM:</strong> {{ $mahasiswa['nim'] }}</p>
                        <p class="card-text"><strong>Prodi:</strong> {{ $mahasiswa['jurusan'] }}</p>
                        <p class="card-text"><strong>Email:</strong> {{ $mahasiswa['email'] }}</p>
                        <p class="card-text"><strong>Kampus:</strong> {{ $mahasiswa['kampus'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-white text-dark border-top text-center py-3 mt-auto">
        <div class="container">
            <p>&copy; {{ date('Y') }} UNPAM. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
