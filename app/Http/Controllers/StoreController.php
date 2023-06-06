<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $stores = Store::where('status', 0)->with('userd')->paginate(15);
        return view('dashboard', compact('stores'));
    }

    public function allStores()
    {
        $stores = Store::where('status', 1)->with('userd');
        if(request()->get('search')){
            $stores = $stores->where(function($s){
                $s->where('store_name','like','%'.request()->get('search').'%')
                ->orWhere('store_ar_name','like','%'.request()->get('search').'%');
            });
        }
        $stores = $stores->paginate(15);
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
}
