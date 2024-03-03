<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email| unique:users',
            'password' => 'required|min:6',
            'username' => 'required | unique:users',
            'phone' => 'required|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "data" => $validator->errors(), 'statusCode' => 200, "message" => 'Invalid'
            ], 422);
        }
//        try{
            $data = [
                'username'=>$request->username,
                'password'=>Hash::make($request->password),
                'name'=>$request->username,
                'phone'=> $request->phone,
               'email'=> $request->email
            ];
            $user = User::create($data);
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $user->attachRole(2);

//            if($request->has('phonse')){
                if($this->sendOTP($user)){
                    return response()->json([
                        "data" => null, 'statusCode' => 200, "message" => 'OTP sent to your provided number!'
                    ], 200);
                }else{
                    return response()->json([
                        "data" => null, 'statusCode' => 200, "message" => 'Something went wrong with OTP!'
                    ], 200);
                }
//            }else{
//                return response()->json([
//                    "data" => [
//                        "token" => $token,
//                        "user" => User::with('country','region')->whereId($user->id)->first()
//                    ], 'statusCode' => 200, "message" => 'success'
//                ], 200);
//            }
//        }catch(Exception $ex){
//            return response()->json([
//                "data" => null, 'statusCode' => 400, "message" => $ex
//            ], 200);
//        }
    }

    private function sendOTP(User $user){
        $code = str_pad(mt_rand(1, 9999), 6, '0', STR_PAD_LEFT);
        if($user->update(['otp'=>$code])){
            $sessionId = Carbon::now()->format('Y-m-d\TH:i:s.u\Z');
            $email = $user->email;
            Mail::raw("Your Verification code is: $code", function ($message) use ($email) {
                $message->to($email)->subject('Account Verification Code');
            });
            return true;
        }
        return false;
    }

    public function login(Request $request){
        try{
            if(Auth::attempt($request->all())){
                $token = Auth::user()->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json([
                    "data" => [
                        "token" => $token,
                        "user" => User::with('country','region')->whereId(auth()->id())->first(),
                        "is_store" => Auth::user()->hasRole('store')
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
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email| unique:users',
            'password' => 'required|min:6',
            'username' => 'required | unique:users',
            'phone' => 'required|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "data" => $validator->errors(), 'statusCode' => 200, "message" => 'Invalid'
            ], 422);
        }
        try{
            $data = [
                'username'=>$request->username,
                'country'=>$request->country,
                'region'=>$request->region,
                'password'=>Hash::make($request->password),
                'name'=>$request->username,
                'phone'=>$request->phone,
                'email' => $request->email
            ];
            $user = User::create($data);
            Store::create([
                'store_name'=>$request->english_storename,
                'store_ar_name'=>$request->arabic_storename,
                'user_id'=>$user->id,
            ]);
//            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $user->attachRole(3);

//            if($request->has('phone')){
            if($this->sendOTP($user)){
                return response()->json([
                    "data" => null, 'statusCode' => 200, "message" => 'OTP sent to your provided number!'
                ], 200);
            }else{
                return response()->json([
                    "data" => null, 'statusCode' => 200, "message" => 'Something went wrong with OTP!'
                ], 200);
            }
//            }else{
//                return response()->json([
//                    "data" => [
//                        "token" => $token,
//                        "user" => User::with('country','region')->whereId($user->id)->first()
//                    ], 'statusCode' => 200, "message" => 'success'
//                ], 200);
//            }
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'can not create a user!'
            ], 200);
        }
    }

    public function resend(Request $request){
        try{
            $user = User::where('phone',$request->phone)->first();
            if($this->sendOTP($user)){
                return response()->json([
                    "data" => null, 'statusCode' => 200, "message" => 'OTP sent to your provided number!'
                ], 200);
            }else{
                return response()->json([
                    "data" => null, 'statusCode' => 400, "message" => 'Something went wrong, please try again!'
                ], 200);
            }
        }catch(Exception $ex){
            Log::error($ex);
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'Something went wrong, please try again!'
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
                "data" => null, 'statusCode' => 400, "message" => 'can not update user!'
            ], 200);
        }
    }

    public function update_store(Request $request){
        try{
            $store = Store::where('user_id',auth()->id())->first();
            $store->update($request->all());
            return response()->json([
                "data" => null, 'statusCode' => 200, "message" => 'success'
            ], 200);
        }catch(Exception $ex){
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'can not update store!'
            ], 200);
        }
    }

    public function verify(Request $request){
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'otp' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "data" => $validator->errors(), 'statusCode' => 200, "message" => 'Invalid'
            ], 422);
        }
        try{
            $user = User::with('country','region')->where('email',$request->email)->firstOrFail();
            Log::info($request->email);
            Log::info($user->otp);
            Log::info( $request->otp);
            if($user->otp === $request->otp){
                $user->update(['otp'=>null]);
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json([
                    "data" => [
                        "token" => $token,
                        "user" => $user,
                        "is_store" => $user->hasRole('store')
                    ], 'statusCode' => 200, "message" => 'success'
                ], 200);
            }else{
                return response()->json([
                    "data" => null, 'statusCode' => 400, "message" => 'OTP Missmatched!'
                ], 200);
            }
        }catch(Exception $ex){
            Log::error($ex);
            return response()->json([
                "data" => null, 'statusCode' => 400, "message" => 'Something went wrong!'
            ], 200);
        }

    }

    public function forget(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT){
            return $status;
        }else{
            return $status;
        }
    }

   public function logout()
    {
        $auth = Auth::user();
        if ($auth->tokens()->delete()) {
            return response()->json(["status" => "success"], 200);
        }
        return response()->json(["status" => "error"], 400);
    }
}
