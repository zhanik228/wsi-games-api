<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Score;
use App\Models\GameVersion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameController extends Controller
{
    public function index(CustomRequest $request) {

        $request->validate([
            'sortBy' => 'in:title,popular,uploaddate',
            'sortDir' => 'in:asc,desc'
        ]);

        $page = $request->query('page', 0);
        $size = $request->query('size', 10);
        $sortBy = $request->query('sortBy', 'title');
        $sortDir = $request->query('sortDir', 'asc');

        $query = Game::query();

        if ($sortBy === 'popular') {
            $query->with(['gameVersions.scores'])
                // Calculate the sum of scores for each game across all versions
                ->withCount([
                    'gameVersions as scoreCount' => function ($query) {
                        $query->selectRaw('coalesce(sum(scores), 0)');
                    }
                ])
                // Order by the calculated scoreCount
                ->orderBy('scoreCount', $sortDir);
        } else if ($sortBy === 'uploaddate') {
            $query->with(['gameVersion' => function ($query) {
                $query->latest('created_at');
            }])->orderBy('gameVersion.created_at', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $games = $query->paginate($size, ['*'], 'page', $page + 1);

        return response()->json([
            'page' => $games->currentPage() - 1,
            'size' => $games->perPage(),
            'totalElements' => $games->total(),
            'content' => collect($games->items())->map(function ($game) {
                return [
                    'slug' => $game->slug,
                    'title' => $game->title,
                    'description' => $game->description,
                    'thumbnail' => $game->thumbnail,
                    'uploadTimestamp' => optional($game->gameVersion)->created_at,
                    'author' => $game->author->username,
                    'scoreCount' => $game->scoreCount,
                ];
            })
        ]);
    }

    public function store(CustomRequest $request) {
        $request->validate([
            'title' => 'required|min:3|max:60',
            'description' => 'required|min:0|max:200'
        ]);

        $slug = Str::slug($request->title);
        $slugExists = Game::where('slug', $slug)->first();

        if ($slugExists) {
            return response()->json([
                'status' => 'invalid',
                'slug' => 'Game title already exists'
            ], 400);
        }

        $game = new Game();
        $game->author_id = Auth::guard('sanctum')->id();
        $game->title = $request->title;
        $game->slug = $slug;
        $game->description = $request->description;
        $game->created_at = now();
        $game->save();

        return response()->json([
            'status' => 'success',
            'slug' => $slug
        ], 201);
    }

    public function show(Game $game) {
            return [
                'slug' => $game->slug,
                'title' => $game->title,
                'description' => $game->description,
                'thumbnail' => $game->thumbnail,
                'uploadTimestamp' => optional($game->gameVersion)->created_at,
                'author' => $game->author->username,
                'scoreCount' => $game->scoreCount,
            ];
    }

    public function serveGame($slug, $version) {
        $game = Game::where('slug', $slug)->first();
        $fileExists = Storage::disk('public')
            ->exists('games/'.$game->id.'/'.$version.'/index.html');
        if ($fileExists) {
            return Storage::disk('public')
                ->response('games/'.$game->id.'/'.$version.'/index.html');
        }

        return response()->json([
            'status' => 'not found',
            'message' => 'Not found'
        ], 404);
    }

    public function update(Request $request, Game $game) {

        $game->title = $request->title ?? $game->title;
        $game->description = $request->description ?? $game->description;
        $game->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function destroy(Game $game) {
        foreach ($game->gameVersions as $gameVersion) {
            foreach ($gameVersion->scores as $score) {
                $score->delete();
            }
            $gameVersion->delete();
        }

        $game->delete();

        return response('', 204);
    }

    public function upload(Request $request, Game $game) {
        $request->validate([
            'zipfile' => 'required|mimes:zip|max:10240'
        ]);

        $zipFile = $request->file('zipfile');

        $version = (int) substr($game->gameVersion->version, 1) + 1;
        $permanentDir = "games/$game->id/".'v'.$version.'/';
        Storage::disk('public')->makeDirectory($permanentDir);
        $extractPath = storage_path("app/public/{$permanentDir}");

        $zip = new \ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            foreach ($game->gameVersions as $gameVersion) {
                $gameVersion->delete();
            }

            $gameVersion = new GameVersion();
            $gameVersion->game_id = $game->id;
            $gameVersion->version = 'v'.$version;
            $gameVersion->path = 'games/'.$game->id.'/v'.$version;
            $gameVersion->updated_at = now();
            $gameVersion->created_at = now();
            $gameVersion->save();

            return response('Uploaded successfully');
        } else {
            return response('Could not open zip');
        }
    }

    public function user($username) {
        $user = User::with(['authoredGames'])->where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'not-found',
                'message' => 'Not found'
            ], 404);
        }

        $authoredGames = $user->authoredGames;
        if (auth('sanctum')->user()->id == $user->id) {
            $authoredGames = $user->authoredGamesAuthor;
        }

        $highscores = $user->highscores;

        return [
            'username' => $user->username,
            'registeredTimestamp' => $user->created_at,
            'authoredGames' => collect($authoredGames)->map(function ($game) {
                return [
                    'slug' => $game->slug,
                    'title' => $game->title,
                    'description' => $game->description
                ];
            }),
            'highscores' => [
                collect($highscores)->map(function ($highscore) {
                    return [
                        'game' => [
                            'slug' => $highscore->scoreGameVersion->game->slug,
                            'title' => $highscore->scoreGameVersion->game->title,
                            'description' => $highscore->scoreGameVersion->game->description
                        ],
                        'score' => $highscore->score,
                        'timestamp' => $highscore->created_at
                    ];
                }),
            ]
        ];
    }

    public function scores(Game $game) {
        return [
            'scores' => collect($game->gameVersions)->flatMap(function ($gameVersion) {
                return collect($gameVersion->scores)->map(function ($score) {
                    return [
                        'username' => $score->user->username,
                        'score' => (int) $score->score,
                        'timestamp' => $score->created_at
                    ];
                })->sortByDesc('score');
            })
        ];
    }

    public function postScore(Request $request, Game $game) {
        $request->validate([
            'score' => 'required'
        ]);
        $score = new Score();
        $score->user_id = $request->user('sanctum')->id;
        $score->game_version_id = $game->gameVersion->id;
        $score->score = $request->score;
        $score->updated_at = now();
        $score->created_at = now();
        $score->save();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
