<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameVersion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $player1 = User::where('username', 'player1')->firstOrFail();
        $player2 = User::where('username', 'player2')->firstOrFail();
        $dev1 = User::where('username', 'dev1')->firstOrFail();
        $dev2 = User::where('username', 'dev2')->firstOrFail();

        $game1 = Game::where('slug', 'demo-game-1')->firstOrFail();
        $game2 = Game::where('slug', 'demo-game-2')->firstOrFail();

        $game1v1 = GameVersion::withTrashed()
            ->where('game_id', $game1->id)
            ->where('version', 'v1')
            ->firstOrFail();
        $game1v2 = GameVersion::withTrashed()
            ->where('game_id', $game1->id)
            ->where('version', 'v2')
            ->firstOrFail();
        $game2v1 = GameVersion::withTrashed()
            ->where('game_id', $game2->id)
            ->where('version', 'v1')
            ->firstOrFail();

        $scores = [
            [
                'user_id' => $player1->id,
                'game_version_id' => $game1v1->id,
                'score' => 10.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $player1->id,
                'game_version_id' => $game1v1->id,
                'score' => 15.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $player1->id,
                'game_version_id' => $game1v2->id,
                'score' => 12.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $player2->id,
                'game_version_id' => $game2v1->id,
                'score' => 20.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $player2->id,
                'game_version_id' => $game2v1->id,
                'score' => 30.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $dev1->id,
                'game_version_id' => $game1v2->id,
                'score' => 1000.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $dev1->id,
                'game_version_id' => $game1v2->id,
                'score' => -300.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $dev2->id,
                'game_version_id' => $game1v2->id,
                'score' => 5.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'user_id' => $dev2->id,
                'game_version_id' => $game2v1->id,
                'score' => 200.0,
                'updated_at' => now(),
                'created_at' => now()
            ],
        ];

        DB::table('scores')->insert($scores);
    }
}
