<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'nama' => 'Admin ITSK',
            'email' => 'admin@itsk.ac.id',
            'password' => 'admin123',
            'role' => 'admin',
            'prodi' => 'TI'
        ]);

        // Dosen
        User::create([
            'nama' => 'Dosen Satu',
            'email' => 'dosen1@itsk.ac.id',
            'password' => 'dosen123',
            'role' => 'dosen',
            'prodi' => 'Farmasi'
        ]);

        // Mahasiswa
        User::create([
            'nama' => 'Mahasiswa Satu',
            'email' => 'mhs1@itsk.ac.id',
            'password' => 'mhs123',
            'role' => 'mahasiswa',
            'prodi' => 'Keperawatan'
        ]);
    }
}
