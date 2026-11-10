<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // DATA LOGIN ADMIN: ubah nama, email, dan password di bawah ini.
        // Jalankan ulang: php artisan db:seed --class=AdminSeeder
        // Email yang sama akan diperbarui, termasuk password; tidak membuat duplikat.
        $admin = User::firstOrNew([
            'email' => 'kevino@admin.com',
        ]);

        $admin->name = 'kevino';
        $admin->password = Hash::make('123');
        $admin->is_admin = true;
        $admin->save();
    }
}
