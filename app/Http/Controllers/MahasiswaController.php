<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa =
            [
                'nim' => '241011750132',
                'nama' => 'Kevino Prianda',
                'jurusan' => 'Sistem Informasi',
                'email' => 'kevinoprianda10@gmail.com',
                'kampus' => 'Universitas Pamulang',
                'status' => 'AKTIF'

            ];
        return view('page.profile', compact('mahasiswa'));
    }
}
