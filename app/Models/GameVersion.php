<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GameVersion extends Model
{
    use HasFactory, SoftDeletes;

    public function scores() {
        return $this->hasMany(Score::class, 'game_version_id');
    }

    public function game() {
        return $this->belongsTo(Game::class, 'game_id')->withTrashed();
    }

    public function thumbnailExists() {
        return Storage::disk('public')->exists('games/'.$this->game_id.'/'.$this->version.'/thumbnail.png');
    }
}
