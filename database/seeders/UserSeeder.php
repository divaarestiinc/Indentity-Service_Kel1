<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Prodi',
            'email' => 'adminprodi@mail.test',
            'password' => Hash::make('password123'),
            'role' => 'admin_prodi'
        ]);
        User::create([
            'name' => 'Admin Poli',
            'email' => 'adminpoli@mail.test',
            'password' => Hash::make('password123'),
            'role' => 'admin_poli'
        ]);
    }
}
