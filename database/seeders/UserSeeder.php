<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'islam',
            'email' => 'islam.walied96@gmail.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        User::create([
            'name' => 'dodo',
            'email' => 'mawadahelmashad@std.mans.edu.eg',
            'password' => Hash::make('mawadadodo'),
            'remember_token' => Str::random(10),
        ]);
    }
}
