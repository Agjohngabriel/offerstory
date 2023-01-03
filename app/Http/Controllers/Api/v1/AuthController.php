<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $data = [
                'username'=>$request->username,
                'password'=>Hash::make($request->password),
                'name'=>$request->username
            ];
    
            if($request->has('phone')){
                $data['phone']= $request->phone;
            }else{
                $data['email']= $request->email;
            }
            $user = User::create($data);
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $user->attachRole(2);
            return response()->json([
                "data" => [
                    "token" => $token,
                    "user" => User::with('country','region')->whereId($user->id)->first()
                ], 'statusCode' => 200, "message" => 'success'
            ], 200);
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'can not create a user!'
            ], 200);
        }
    }

    public function login(Request $request){
        try{
            if(Auth::attempt($request->all())){
                $token = Auth::user()->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json([
                    "data" => [
                        "token" => $token,
                        "user" => User::with('country','region')->whereId(auth()->id())->first()
                    ], 'statusCode' => 200, "message" => 'success'
                ], 200);
            }else{
                return response()->json([
                    "data" => null, 'statusCode' => 400, "message" => 'Either username or password id incorrect'
                ], 200);
            }
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'Something went wrong!'
            ], 200);
        }
    }

    public function store_register(Request $request){
        try{
            $data = [
                'username'=>$request->username,
                'country'=>$request->country,
                'region'=>$request->region,
                'password'=>Hash::make($request->password),
                'name'=>$request->username
            ];
    
            if($request->has('phone')){
                $data['phone']= $request->phone;
            }else{
                $data['email']= $request->email;
            }
            $user = User::create($data);
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $user->attachRole(3);
            return response()->json([
                "data" => [
                    "token" => $token,
                    "user" => User::with('country','region')->whereId($user->id)->first()
                ], 'statusCode' => 200, "message" => 'success'
            ], 200);
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'can not create a user!'
            ], 200);
        }
    }

    public function store_login(Request $request){
        try{
            if(Auth::attempt($request->all())){
                $token = Auth::user()->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json([
                    "data" => [
                        "token" => $token,
                        "user" => User::with('country','region')->whereId(auth()->id())->first()
                    ], 'statusCode' => 200, "message" => 'success'
                ], 200);
            }else{
                return response()->json([
                    "data" => null, 'statusCode' => 400, "message" => 'Either username or password id incorrect'
                ], 200);
            }
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'Something went wrong!'
            ], 200);
        }
    }

    public function update(Request $request){
        try{
            auth()->user()->update($request->all());
            return response()->json([
                "data" => null, 'statusCode' => 200, "message" => 'success'
            ], 200);
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'can not update a user!'
            ], 200);
        }
    }
}
