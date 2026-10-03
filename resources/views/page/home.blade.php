@extends('layouts.app')

@section('title','home')

@section('content')
<div class="container flex-grow-1">
    <div class="col-md-12">
        <h2>Selamat Datang</h2>
        <p class="text-muted">Ini halaman utama web profile mahasiswa prodi SI UNPAM</p>
        <a href="{{ url('/profile') }}" class="btn btn-success">Lihat Profile</a>
    </div>
</div>

@endsection

{{--
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container py-5 flex-grow-1">
    <div class="row align-items-center min-vh-50">
        <div class="col-lg-7">
            <span class="badge text-bg-primary mb-3">Website Mahasiswa</span>

            <h1 class="display-5 fw-bold mb-3">
                Selamat Datang di<br>
                Profile Mahasiswa SI UNPAM
            </h1>

            <p class="lead text-muted mb-4">
                Kenali informasi mahasiswa Program Studi Sistem Informasi
                Universitas Pamulang dengan lebih mudah.
            </p>

            <a href="{{ url('/profile') }}" class="btn btn-success btn-lg px-4">
                Lihat Profile
            </a>
        </div>

        <div class="col-lg-5 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Program Studi Sistem Informasi</h5>
                    <p class="text-muted mb-0">
                        Halaman ini berisi informasi profil mahasiswa,
                        program studi, dan kampus Universitas Pamulang.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
