<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\User;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $stores = Store::where('status', 0)->with('userd')->OrderBy('id','desc')->paginate(15);
        return view('dashboard', compact('stores'));
    }

     public function allStores()
    {
        $stores = Store::where('status', 1);
        // //$country = Contry::where("title",request()->get('search'))->first();
        // if(request()->get('search')){
        //     $stores = $stores->where(function($s){
        //         $s->where('store_name','like','%'.request()->get('search').'%')
        //         ->orWhere('store_ar_name','like','%'.request()->get('search').'%');
        //     });
        // }
        // $stores = $stores->paginate(15);

        $searchTerm = request()->get('search');
        //$stores = Store::query();
        if ($searchTerm) {
            // $stores->orWhere('store_name', 'like', '%' . $searchTerm . '%')
            // ->orWhere('store_ar_name', 'like', '%' . $searchTerm . '%');
    
            // Check if the search term matches any country or region names
            $matchedCountries = User::whereHas('country', function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%');
            })->pluck('id');
    
            $matchedRegions = User::whereHas('region', function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%');
            })->pluck('id');

            
    
            // Retrieve stores belonging to the matched countries and regions
            //$stores->orWhereIn('user_id', $matchedCountries)
           // ->orWhereIn('user_id', $matchedRegions);

            $stores = Store::orWhere('store_ar_name', 'like', '%' . $searchTerm . '%')->orWhereIn('user_id', $matchedCountries)->orWhereIn('user_id', $matchedRegions)->where(["status" => 1]);

       
        }
    
        $stores = $stores->with('userd')->paginate(15);
        $search = request()->get('search');
        return view('stores', compact('stores','search'));
    }


    public function approve($id)
    {
        $store = Store::where('id', $id)->first();
        $store->update(['status'=>1]);
        return redirect()->back();
    }

    public function disapprove($id)
    {
        $store = Store::where('id', $id)->first();
        $store->update(['status'=>0]);
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }

    public function delete($id){
        $store = Store::whereId($id)->first();
        if($store->delete()){
            return redirect()->back();
        }
        return redirect()->back();
    }
}
