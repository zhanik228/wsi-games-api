<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isBlocked() {
        if ($this->deleted_at && $this->delete_reason) {
            return true;
        }
        return false;
    }

    public function blockReason() {
        if ($this->deleted_at && $this->delete_reason) {
            return $this->delete_reason;
        }
        return false;
    }

    public function authoredGames() {
        return $this->hasMany(Game::class, 'author_id')
            ->withTrashed()
            ->leftJoin('game_versions', 'game_versions.game_id', 'games.id')
            ->whereNotNull('game_versions.game_id')
            ->groupBy('games.id');
    }

    public function authoredGamesAuthor() {
        return $this->hasMany(Game::class, 'author_id')
            ->withTrashed()
            ->leftJoin('game_versions', 'game_versions.game_id', 'games.id')
            ->groupBy('games.id');
    }

    public function highscores() {
        return $this->hasMany(Score::class);
    }
}
