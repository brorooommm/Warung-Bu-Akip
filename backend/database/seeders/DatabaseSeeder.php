<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin/test
        User::factory()->create([
            'username' => 'Test User', // ganti dari 'name' jadi 'username'
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // biar bisa login juga
        ]);

        // Jalankan seeder kategori
        $this->call(CategorySeeder::class);
    }
}
