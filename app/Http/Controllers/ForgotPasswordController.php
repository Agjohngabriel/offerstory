<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                "data" => null, 'statusCode' => 404, "message" => 'User not found'
            ], 404);
        }
        $code = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $sessionId = Carbon::now()->format('Y-m-d\TH:i:s.u\Z');
        DB::table('create_forgot_password_reset_table')->insert([
            'email' => $email,
            'token' => $code,
            'session_id' => $sessionId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Mail::raw("Your reset code is: $code", function ($message) use ($email) {
            $message->to($email)->subject('Password Reset Code');
        });

        return response()->json(['message' => 'Reset code sent', 'session_id' => $sessionId], 200);
    }
    public function reset(Request $request)
    {
        $email = $request->input('email');
        $sessionId = $request->input('session_id');
        $code = $request->input('code');

        $resetData = DB::table('create_forgot_password_reset_table')
            ->where('email', $email)
            ->where('session_id', $sessionId)
            ->where('token', $code)
            ->first();

        if (!$resetData) {
            return response()->json(['message' => 'Invalid credentials'], 400);
        }
        $token = User::where('email', $email)->first()->createToken('Laravel Password Grant Client')->accessToken;
        DB::table('create_forgot_password_reset_table')
            ->where('email', $email)
            ->where('session_id', $sessionId)
            ->delete();

        return response()->json(['message' => 'Password reset successful', 'token' => $token]);
    }
    public function changePassword(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        $validatedData = $request->validate([
            'password' => 'required|confirmed', // Ensure the password field matches the password_confirmation field
        ]);

        $newPassword = $validatedData['password'];

        // Change the password for the authenticated user
        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}
