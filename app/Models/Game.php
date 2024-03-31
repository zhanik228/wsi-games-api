<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['thumbnail', 'scoreCount', 'gamePath'];

    public function getScoreCountAttribute() {
        $count = 0;
        foreach($this->gameVersions as $version) {
            foreach($version->scores as $score) {
                $count += $score->score;
            }
        }

        return $count;
    }

    public function gamePath() {
        return '/games/'.$this->slug.'/'.$this->version.'/';
    }


    public function getGamePathAttribute() {
        if ($this->gameVersion) {
            return $this->gamePath();
        }
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function gameVersions() {
        return $this->hasMany(GameVersion::class, 'game_id')->withTrashed();
    }

    public function gameVersion() {
        return $this->hasOne(GameVersion::class, 'game_id')
            ->whereNull('deleted_at');
    }

    public function thumbnailPath() {
        return '/games/'.$this->slug.'/'.$this->version.'/thumbnail.png';
    }

    public function getThumbnailAttribute() {
        if ($this->gameVersion && $this->gameVersion->thumbnailExists()) {
            return $this->thumbnailPath();
        }
        return null;
    }
}
