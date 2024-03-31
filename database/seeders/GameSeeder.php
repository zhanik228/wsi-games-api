<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dev1 = User::where('username', 'dev1')->first();
        $dev2 = User::where('username', 'dev2')->first();

        $games = [
            [
                'title' => 'Demo Game 1',
                'slug' => 'demo-game-1',
                'description' => 'This is demo game 1',
                'author_id' => $dev1->id,
                'created_at' => now()
            ],
            [
                'title' => 'Demo Game 2',
                'slug' => 'demo-game-2',
                'description' => 'This is demo game 2',
                'author_id' => $dev2->id,
                'created_at' => now()->subDays(2)
            ],
        ];

        DB::table('games')->insert($games);
    }
}
