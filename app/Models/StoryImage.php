<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function views(){
        return $this->belongsToMany(User::class,'story_views','user_id','story_image_id');
    }
}
