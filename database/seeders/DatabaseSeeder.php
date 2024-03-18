<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $email = 'admin@example.com';
        $password = 'password';
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => $email,
            'company' => '', // Ajoutez le nom de l'entreprise que vous voulez
            'phone_number' => '', // Ajoutez un numéro de téléphone valide
            'password' => Hash::make($password),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
