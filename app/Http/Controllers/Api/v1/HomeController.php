<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contry;
use App\Models\Region;
use App\Models\Store;
use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function countries(){
        $data = Contry::all();
        return response()->json([
            "data" => [
                "countries" => $data
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function regions(){
        $data = Region::all();
        return response()->json([
            "data" => [
                "regions" => $data
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function countries_regions($id){
        $data = Region::where('country_id',$id)->get();
        return response()->json([
            "data" => [
                "regions" => $data
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function categories(){
        $data = Category::all();
        return response()->json([
            "data" => [
                "categories" => $data
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function home(){
        $stories = [];
        if(auth()->user()){
            $stores = Store::whereIn('id',auth()->user()->followings()->pluck('store_id')->toArray())->get();
            foreach($stores as $store){
                if($store->is_stories){
                    $stories[] = $store;
                }
            }
        }
        $categories = Category::all();
        return response()->json([
            "data" => [
                'stories'=>$stories,
                "categories" => $categories
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function stores(Request $request, $id){
        $category = Category::whereId($id)->first();
        
        // $stores = Store::whereHas('available_stories',function($r) use ($id){
        //     $r->where('category_id',$id);
        // })->where(function($r) use ($request){
        //     $r->whereHas('branches',function($q) use ($request){
        //         $q->where('region_id',$request->region_id);
        //     })->orwhereHas('user',function($s) use ($request){
        //         $s->where('region',$request->region_id);
        //     });
        // })->where('status',1)->get();


        $stores = Store::whereHas('available_stories',function($r) use ($id){
            $r->where('category_id',$id);
        })->where('status',1)->get();
        $result = [];
        foreach($stores as $store){
            $available_stories = [];
            foreach($store->available_stories as $story){
                // if($story->id != 224){
                //     dd("outside if",$stores->count(),$story->id,$request->region_id, $story->regions->toArray(), $story->regions()->where('region_id', $request->region_id)->exists(), $story->regions()->where('regions.id', $request->region_id)->toSql());
                // }
                if($story->regions()->where('regions.id', $request->region_id)->exists()){
                    // dd($stores->count(), $story->regions()->where('regions.id', $request->region_id)->exists());
                    $available_stories[] = $story;
                    
                }
                $result[] = [
                    'id' => $store->id,
                    'user_id' => $store->user_id,
                    'store_name' => $store->store_name,
                    'store_ar_name' => $store->store_ar_name,
                    'description' => $store->description,
                    'store_icon' => $store->store_icon,
                    'store_bg' => $store->store_bg,
                    'created_at' => $store->created_at,
                    'updated_at' => $store->updated_at,
                    'visits' => $store->visits,
                    'is_stories' => $store->is_stories,
                    'is_followed' => $store->is_followed
                ];
                $result[]['available_stories'] = $available_stories;
            }
        }
        return response()->json([
            "data" => [
                'category'=>$category,
                'stores'=>$result,
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function get_story($id, Request $request){
        $store = Store::with('available_stories')->whereId($id)->first();
        $media = [];
        foreach($store->available_stories as $story){
            // dd($request->get('category_id') && $story->category_id == $request->get('category_id'),$story->category_id, $request->get('category_id'));
            if($request->get('category_id')){
                if($story->category_id == $request->get('category_id')){
                    foreach($story->media as $item){
                        if(auth()->user()){
                            $item->views()->syncWithoutDetaching(auth()->user());
                        }
                        $item->expiry = $story->expiry;
                        $media[] = $item;
                    }
                }
            }else{
                foreach($story->media as $item){
                    if(auth()->user()){
                        $item->views()->syncWithoutDetaching(auth()->user());
                    }
                    $item->expiry = $story->expiry;
                    $media[] = $item;
                }
            }
        }
        return response()->json([
            "data" => [
                'stories'=>$media
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function search(Request $request){
        $stores = Store::where(function($r)use($request){
            $r->where('store_name','like','%'.$request->search_test.'%')->orWhere('store_ar_name','like','%'.$request->search_test.'%');
        })->where('status',1)->get();
        return response()->json([
            "data" => [
                'results'=>$stores
            ], 'statusCode' => 200, "message" => 'success'
        ], 200);
    }

    public function get_store(Request $request, $id){
        $store = Store::with('branches','stories')->withCount('followers','stories')->where('id',$id)->where('status',1)->first();
        $visits = (int) $store->visits + 1;
        $store->update(['visits'=>$visits]);
        return response()->json([
            "data" => [
                'store'=>$store
            ], 'statusCode' => 200, "message" => 'success!'
        ], 200);
    }
}
