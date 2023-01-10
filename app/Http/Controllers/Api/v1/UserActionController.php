<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserActionController extends Controller
{
    public function follow($id){
        try{
            $store = Store::whereId($id)->firstOrFail();
            if(auth()->user()->followings()->where('store_id',$id)->exists()){
                auth()->user()->followings()->detach($id);
            }else{
                auth()->user()->followings()->attach($id);
            }
            return response()->json([
                "data" => null, 'statusCode' => 200, "message" => 'success!'
            ], 200);
        }catch(Exception $ex){
            Log::error($ex);
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'Something went wrong!'
            ], 200);
        }
    }
}
