<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $projects = [
            [
                'title' => 'Sistem Informasi Akademik',
                'description' => 'Aplikasi berbasis web untuk pengeloaan data mahasiswa, jadwal kuliah dan nilai perkuliahan',
                'teknologi' => 'Laravel & Bootstrap',
                'image' => 'project1.jpg',
                'status' => 'Selesai',
            ],
            [
                'title' => 'E-Commerce SEO Optimization',
                'description' => 'Aplikasi optimalisasi struktur heading dan indexing halaman web toko online',
                'teknologi' => 'PHP & Google Search Console',
                'image' => 'Project2.jpg',
                'status' => 'In Progress',
            ],
            [
                'title' => 'Redesign Cover & Branding',
                'description' => 'Perencanaan elemen grafis personal branding dan desing sampul buku rekayasa web',
                'teknologi' => 'Figma & Canva',
                'image' => 'project1.jpg',
                'status' => 'Selesai',
            ],
            [
                'title' => 'Portal mahasiswa',
                'description' => 'Melihat data mahasiswa',
                'teknologi' => 'Laravel',
                'image' => 'project3.jpg',
                'status' => 'Selesai',
            ],
        ];
        foreach ($projects as $project) {
            \App\Models\Project::create($project);
        }
    }
}
