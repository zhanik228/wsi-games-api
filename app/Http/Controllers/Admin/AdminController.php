<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index() {
        return view('admin.login');
    }

    public function toLoginPage() {
        return redirect()->route('admin.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only([
            'username',
            'password'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.admin-list');
        }

        return redirect()->back()->withErrors(['errorMsg' => 'Email or password incorrect']);
    }

    public function logout() {
        Auth::logout();

        return redirect()->route('admin.login');
    }

    public function adminList() {
        $admins = Admin::all();
        return view('admin.list.admin-list', ['admins' => $admins]);
    }

    public function userList() {
        $users = User::withTrashed()->get();
        return view('admin.list.user-list', ['users' => $users]);
    }

    public function userProfile($id) {
        $user = User::withTrashed()->find($id);
        return view('admin.user.profile', ['user' => $user]);
    }

    public function blockUser(Request $request, $id) {
        $user = User::withTrashed()->find($id);

        $user->deleted_at = now();
        $user->delete_reason = $request->block_reason;
        $user->save();

        return redirect()->back();
    }

    public function unblockUser(Request $request,$id) {
        $user = User::withTrashed()->find($id);

        $user->deleted_at = null;
        $user->delete_reason = null;
        $user->save();

        return redirect()->back();
    }

    public function games(Request $request) {
        $games = Game::withTrashed()->get();
        $query = $request->get('query');
        if ($request->has('query')) {
            $games = Game::withTrashed()->where('title', 'LIKE' , '%'.$query.'%')->get();
        }
        return view('admin.games.list', ['games' => $games]);
    }

    public function gameById($id) {
        $game = Game::withTrashed()->find($id);


        return view('admin.games.game', ['game' => $game]);
    }

    public function deleteGame($id) {
        $game = Game::find($id);

        $game->deleted_at = now();
        $game->save();

        return redirect()->back();
    }

    public function getGameFile(Request $request, $slug, $version, $file) {
        $game = Game::where('slug', $slug)->first();
        return Storage::disk('public')->response("games/$game->id/".$game->gameVersion->version.'/'.$file);
    }

    public function deleteScores(Request $request, $id) {
        if ($request->has('delete_method')) {
            if ($request->get('delete_method') == 'game') {
                $game_id = Game::withTrashed()->find($id)->id;
                $gameVersions = GameVersion::where('game_id', $game_id)->get();
                foreach($gameVersions as $gameVersion) {
                    foreach($gameVersion->scores as $score) {
                        $score->delete();
                    }
                }
                return redirect()->back();
            }
            if ($request->get('delete_method') == 'all') {
                $userId = Score::find($id)->user_id;
                Score::where('user_id', $userId)->delete();
                return redirect()->back();
            } else if ($request->get('delete_method') == 'one') {
                Score::find($id)->delete();
                return redirect()->back();
            }
        }
    }
}
