<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'full_name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => ['admin'],
            //'password' => bcrypt('admin@123'), // password
            'password' =>  Hash::make('admin@123'), // password
        ]);

        //insert 3 dummy users
        User::factory(3)->create();
    }
}
