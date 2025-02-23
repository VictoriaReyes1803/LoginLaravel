<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationEmail;
use Illuminate\Support\Facades\Cache;


class RegisterController extends Controller
{
    /**
     * Register a new user.
     * 
     * This method validates the incoming registration data, creates a new user in the database,
     * and sends an activation email to the user for account verification.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    { 
        
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => false,
        ]);
       
        Mail::to($user->email)->send(new ActivationEmail($user));

        return response()->json([
            'message' => 'User created successfully, An email has been sent to activate your account',
        ], 201);
    }

     /**
     * Activate the user's account.
     * 
     * This method validates the activation token and activates the user's account if the token is valid.
     * If the user is already active or the token is invalid, appropriate error messages are returned.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(Request $request, User $user)
    {
        
        if(!$request->hasValidSignature()){
            return response()->json([
              "msg" => "Invalid activation link"
            ], 401);
    
          }

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if ($user->is_active) {
            return response()->json([
                'message' => 'User already activated',
            ], 400);
        }

        if ($user->activation_token !== $request->token) {
            return response()->json([
                'message' => 'Invalid activation token',
            ], 400);
        }

        $user->is_active = true;
        $user->save();

        return response()->json([
            'message' => 'User activated successfully',
        ]);
    }

      /**
     * Resend the activation email.
     * 
     * This method allows a user to request a new activation email if they did not receive the first one.
     * It checks that the user exists, that the account is not already active, and that an email 
     * hasn't been sent recently, to prevent spamming.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function resendActivationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->is_active) {
            return response()->json(['message' => 'The account is already active.'], 400);
        }

        $cacheKey = 'resend_email_' . $user->email;
        if (Cache::has($cacheKey)) {
            return response()->json(['message' => 'An email has already been sent recently. Please try again later.'], 429);
        }

        Mail::to($user->email)->send(new ActivationEmail($user));

        Cache::put($cacheKey, true, now()->addMinutes(1));

        return response()->json(['message' => 'Activation email successfully resent.'], 200);
    }
}
