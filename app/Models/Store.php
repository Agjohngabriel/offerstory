<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['is_stories','is_followed'];
    protected $hidden = ['status'];

    public function stories(){
        return $this->hasMany(Story::class,'store_id')->with('media');
    }

    public function available_stories(){
        return $this->stories()->whereDate('expiry','>=',date('Y-m-d'));
    }

    public function getIsFollowedAttribute(){
        if($this->followers()->where('user_id',auth()->id())->exists()){
            return true;
        }
        return false;
    }

    public function getIsStoriesAttribute(){
        $data = $this->available_stories()->get();
        if($data->count()>0){
            return true;
        }
        return false;
    }

    public function followers(){
        return $this->belongsToMany(User::class,'user_followers','store_id','user_id','id','id');
    }

    public function branches(){
        return $this->hasMany(Branch::class,'store_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function userd(){
        return $this->belongsTo(User::class,'user_id')->with('countryd','regiond');
    }
}
