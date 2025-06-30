<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:customers',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = rand(100000, 999999);
        $cacheKey = 'register_' . $request->email;
        $cacheOtpKey = 'otp_' . $request->email;
        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ];
        // Lưu thông tin vào cache 5 phút
        Cache::put($cacheKey, $data, now()->addMinutes(5));
        Cache::put($cacheOtpKey, $otp, now()->addMinutes(5));
        // Gửi mail OTP
        Mail::to($request->email)->send(new OtpMail($otp));
        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email. Please verify to complete registration.'
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $cacheKey = 'register_' . $request->email;
        $cacheOtpKey = 'otp_' . $request->email;
        $cachedOtp = Cache::get($cacheOtpKey);
        $cachedData = Cache::get($cacheKey);
        if (!$cachedOtp || !$cachedData) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP expired or not found.'
            ], 400);
        }
        if ($request->otp != $cachedOtp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP.'
            ], 400);
        }
        // Lưu vào DB
        $customer = Customer::create($cachedData);
        // Xóa cache
        Cache::forget($cacheKey);
        Cache::forget($cacheOtpKey);
        $token = $customer->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Customer registered successfully',
            'data' => [
                'customer' => $customer,
                'token' => $token
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $customer = Customer::where('email', $request->email)->first();
        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'customer' => $customer,
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $cacheKey = 'register_' . $request->email;
        $cacheOtpKey = 'otp_' . $request->email;
        $cachedData = Cache::get($cacheKey);
        if (!$cachedData) {
            return response()->json([
                'status' => 'error',
                'message' => 'No registration data found or expired. Please register again.'
            ], 400);
        }
        $otp = rand(100000, 999999);
        Cache::put($cacheOtpKey, $otp, now()->addMinutes(5));
        Mail::to($request->email)->send(new OtpMail($otp));
        return response()->json([
            'status' => 'success',
            'message' => 'OTP resent to your email.'
        ], 200);
    }
} 