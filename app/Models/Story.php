<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function media(){
        return $this->hasMany(StoryImage::class,'story_id');
    }

    public function regions(){
        return $this->belongsToMany(Region::class,'story_regions','story_id','region_id');
    }
}
