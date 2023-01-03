<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Contry;
use App\Models\Region;
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

    
}
