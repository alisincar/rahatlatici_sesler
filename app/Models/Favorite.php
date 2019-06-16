<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable=['user_id','music_id'];

    public function music(){
        return $this->hasOne(Music::class,'id','music_id')->where('status','1');
    }
}
