<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['parent_id','name','cover_image','status'];
    public function musics(){
       return $this->hasMany(Music::class,'category_id','id')->where('status','1')->with('is_favorite');
    }
    public function parent(){
       return $this->hasOne(Category::class,'id','parent_id');
    }
}
