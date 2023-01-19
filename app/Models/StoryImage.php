<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function views(){
        return $this->hasMany(User::class,'user_id');
    }
}
