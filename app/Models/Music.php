<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $fillable=['category_id','name','cover_image','status','source'];

    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}
