<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'username' => 'admin1',
                'password' => Hash::make('hellouniverse1!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'admin2',
                'password' => Hash::make('hellouniverse2!'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('admins')->insert($admins);
    }
}
