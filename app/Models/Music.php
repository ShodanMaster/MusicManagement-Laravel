<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Music extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'musics';
    protected $guarded = [];

    public function album(){
        return $this->belongsTo(Album::class);
    }

    public function playLists(){
        return $this->belongsToMany(PlayList::class, 'play_list_music');
    }
}
