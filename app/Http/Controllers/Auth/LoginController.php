<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Mail\VerificationEmail;

class LoginController extends Controller
{
    /**
     * Handle user login.
     * 
     * This method validates the incoming login request, checks if the user exists,
     * verifies if the account is active, and handles failed login attempts. If successful,
     * a JWT token is returned along with a verification code sent to the user's email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if(($user = \App\Models\User::where('email', $request->email)->first()) == null )
    {
        return response()->json(['message' => 'User does not exist'], 400);
    }

    if (!$user) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->is_active) {
        return response()->json(['message' => 'Your account is deactivated.'], 403);
    }

    if ($user->failed_login_attempts >= 5) {
        $user->is_active = false;
        $user->save();
        return response()->json(['message' => 'Too many failed attempts. Your account has been locked.'], 403);
    }

    if ($token = JWTAuth::attempt($request->only('email', 'password'))) {

        $user->failed_login_attempts = 0; 
        $user->save();

        $code = rand(100000, 999999);
        $user->verification_token = $code;
        $user->is_verified = false;
        $user->save();
        Mail::to($user->email)->send(new VerificationEmail($code, $user));

        return response()->json(['message' => 'Verification code sent to email', 'token' => $token], 200);
    } else {
        $user->failed_login_attempts += 1;
        $user->save();

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}

     /**
     * Handle user account verification using the verification code.
     * 
     * This method checks if the user is authenticated, validates the verification code,
     * and updates the user's account to be verified. If the code is valid, a new JWT token
     * is generated and returned with a cookie. In case of incorrect code, it increments failed attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function verification(Request $request)
    {

        $request->validate([
            'code' => 'required|numeric',
            'token' => 'required|string',
        ]);
    
        $user = JWTAuth::setToken($request->token)->authenticate();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }
        if ($user->failed_verification_attempts >= 5) {
            $user->is_active = false;
            $user->save();
            return response()->json(['message' => 'Too many failed attempts. Your account has been locked.'], 403);
        }
    
        if ($user->verification_token == $request->code) {
            $user->is_verified = true;
            $user->failed_verification_attempts = 0;
            $user->verification_token = null;
            $user->save();

            $token = JWTAuth::fromUser($user);

        
            $cookie = cookie('jwt', $token, 60);
    
            return response()->json([
                'message' => 'Cuenta verificada',
               
            ])->withCookie($cookie);
        }
        else{
            $user->failed_verification_attempts += 1;
            $user->save();
            return response()->json(['message' => 'Invalid verification code.'],400);
        }
    
        return response()->json(['message' => 'Invalid verification code.'],400);
    }
    
/**
     * Logout the user and invalidate the session.
     * 
     * This method invalidates the user's JWT token, deletes the JWT token from the cookie,
     * and redirects the user to the login page with a success message indicating successful logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
public function logout(Request $request)
{

    $token = JWTAuth::getToken();
        if ($token && JWTAuth::check()) {
            
            JWTAuth::invalidate($token);
           
        }

        Cookie::queue(Cookie::forget('jwt'));
        return redirect()->route('login')->with('success', 'You have logged out successfully.');
}



}




