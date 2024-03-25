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
                'expiry'=>Carbon::parse($request->expiry),
                'description'=>$request->description ?? "",
            ]);
            if($request->region_id){
                $story->regions()->attach($request->region_id);
            }
            if($request->has('photos')){
                foreach($request->photos as $photo){
                    StoryImage::create([
                        'story_id'=>$story->id,
                        'description'=>$photo['description'] ?? "",
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
        $store = Store::with('branches')
            ->withCount('followers', 'stories')
            ->where('user_id', auth()->id())
            ->with(['stories' => function ($query) {
                $query->where('expiry', '>=', now()); // Filter stories with expiry date greater than or equal to current time
            }])
            ->first();
        return response()->json([
            "data" => [
                'store'=>$store
            ], 'statusCode' => 200, "message" => 'success!'
        ], 200);
    }

    //TODO
    public function update(){

    }

    public function delete_branch($id)
    {
        try{
            if(Branch::whereId($id)->delete()){
                return response()->json([
                    "data" => null, 'statusCode' => 200, "message" => 'success!'
                ], 200);
            }
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'error!'
            ], 200);
        }
    }

    public function delete_story($id)
    {
        try{
            if(Story::whereId($id)->delete()){
                return response()->json([
                    "data" => null, 'statusCode' => 200, "message" => 'success!'
                ], 200);
            }
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'error!'
            ], 200);
        }
    }
}
