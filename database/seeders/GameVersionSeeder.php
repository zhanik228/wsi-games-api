<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $game1 = Game::where('slug', 'demo-game-1')->first();
        $game2 = Game::where('slug', 'demo-game-2')->first();

        $gameVersions = [
            [
                'game_id' => $game1->id,
                'version' => 'v1',
                'path' => "games/$game1->id/v1",
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
                'deleted_at' => now(),
            ],
            [
                'game_id' => $game1->id,
                'version' => 'v2',
                'path' => "games/$game1->id/v2",
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'game_id' => $game2->id,
                'version' => 'v1',
                'path' => "games/$game2->id/v1",
                'created_at' => now()->subDays(3),
                'updated_at' => now(),
                'deleted_at' => null
            ]
        ];

        DB::table('game_versions')->insert($gameVersions);
    }
}
