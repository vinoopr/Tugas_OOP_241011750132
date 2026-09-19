<?php

use Illuminate\Support\Facades\Route;

Route::get('/mahasiswa', function () {
    $mahasiswa = [
        'nama' => 'Kevino Prianda',
        'nim' => '241011750132',
        'prodi' => 'Sistem Informasi',
        'email' => 'kevinoprianda11@gmail.com',
        'kampus' => 'Universitas Pamulang',
        'status' => 'AKTIF',
    ];

    return view('mahasiswa', compact('mahasiswa'));
});
