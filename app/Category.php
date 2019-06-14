<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function music(){
       return $this->hasMany(Music::class,'category_id','id');
    }
}
