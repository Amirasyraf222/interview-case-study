<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Amir',
            'email' => 'amir@yahoo.com',
            'password' => Hash::make('qwe123'),
            'created_at' => now(),
        ]);
    }
}
