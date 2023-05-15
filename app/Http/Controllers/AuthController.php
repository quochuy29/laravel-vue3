<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        if (!Auth::attempt($credentials)) {
            return throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $request->session()->invalidate();
        //regenerate CSRF Token
        $request->session()->regenerateToken();

        $request->session()->regenerate();
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'access_token' => $user->createToken('accessToken')->plainTextToken,
            'token_type' => 'Bearer',
            'auth_user' => $user
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logout successfully;'
        ], Response::HTTP_OK);
    }
}
