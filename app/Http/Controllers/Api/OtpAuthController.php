<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Otp;

class OtpAuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
                    ->first();

        // if (! $user) {
        //     return response()->json([
        //         'status' => 0,
        //         'msg' => 'No active user found with that email.',
        //     ]);
        // }

        $otp = rand(1000, 9999);
        $expires = now()->addMinutes(5);

        Otp::create([
            'email' => $request->email,
            'code' => $otp,
            'expires_at' => $expires,
            'used' => false,
        ]);

        // Mail::raw("Your login OTP is: $otp", function ($message) use ($request) {
        //     $message->to($request->email)
        //             ->subject('Your OTP Code');
        // });

        return response()->json([
            'status' => 1,
            'msg' => 'OTP has been sent to your email.',
            'step' => 'otp',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $otpData = Otp::where('email', $request->email)
            ->where('code', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpData) {
            return response()->json([
                'status' => 0,
                'msg' => 'The provided OTP is invalid or has expired.',
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $profileStatus = 0;
        if (! $user) {

            $user = User::create([
                'email' => $request->email,
                'name' => 'Test',
                'password' => 'Test'
            ]);

            // return response()->json([
            //     'status' => 0,
            //     'msg' => 'User record not found.',
            // ]);
           $profileStatus = 0;
        }else{
            $interests = Interests::where('user_id', $user->id)->get();
            if ($interests->isNotEmpty()) {
                $profileStatus = 1;
            }
        }



        // ✅ Mark OTP used
        $otpData->used = true;
        $otpData->save();

        // ✅ Create Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 1,
            'msg' => 'Login success',
            'token' => $token,
            'user_details' => $user,
            'profileStatus' => $profileStatus,
        ]);
    }
}
