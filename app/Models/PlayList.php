<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function musics(){
        return $this->belongsToMany(Music::class, 'play_list_music');
    }
}
