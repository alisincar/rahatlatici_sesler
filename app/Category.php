<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['parent_id','name','cover_image','status'];
    public function music(){
       return $this->hasMany(Music::class,'category_id','id')->where('status','1');
    }
    public function parent(){
       return $this->hasOne(Category::class,'id','parent_id');
    }
}
