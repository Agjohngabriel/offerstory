<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Store;
use App\Models\Story;
use App\Models\StoryImage;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreActionController extends Controller
{
    public function story(Request $request){
        try{
            $story = Story::create([
                'store_id'=>Store::where('user_id',auth()->id())->first()->id,
                'category_id'=>$request->category_id,
                'region_id'=>$request->region_id,
                'expiry'=>Carbon::parse($request->expiry),
                'description'=>$request->description,
            ]);
            if($request->has('photos')){
                foreach($request->photos as $photo){
                    StoryImage::create([
                        'story_id'=>$story->id,
                        'description'=>$photo['description'],
                        'media_type'=>$photo['media_type'],
                        'media_url'=>$photo['media_url'],
                    ]);
                }
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

    public function upload(Request $request){
        try{
            $media = [];
            if($request->has('media')){
                foreach($request->media as $item){
                    $file = Storage::disk('public')->put('stories', $item);
                    $url = Storage::url($file);
                    $media[] = $url;
                }
            }
            return response()->json([
                "data" => [
                    'media'=>$media
                ], 'statusCode' => 200, "message" => 'success!'
            ], 200);
        }catch(Exception $ex){
            Log::error($ex);
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'Something went wrong!'
            ], 200);
        }
    }

    public function branch(Request $request){
        $branch = Branch::create([
            'store_id'=>Store::where('user_id',auth()->id())->first()->id,
            'branch_name'=>$request->branch_name,
            'branch_ar_name'=>$request->branch_ar_name,
            'phone_number'=>$request->phone_number,
            'region_id'=>$request->region_id,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'description'=>$request->description,
            'location'=>$request->location,
        ]);
        return response()->json([
            "data" => [
                'branch'=>$branch
            ], 'statusCode' => 200, "message" => 'success!'
        ], 200);
    }

    public function get_store(Request $request){
        $store = Store::with('branches','stories')->withCount('followers','stories')->where('user_id',auth()->id())->first();
        return response()->json([
            "data" => [
                'store'=>$store
            ], 'statusCode' => 200, "message" => 'success!'
        ], 200);
    }

    //TODO
    public function update(){

    }
}
