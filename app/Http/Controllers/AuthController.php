<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        
        $validator = Validator::make(request()->all(), [
            "email" => "required|email",
            "password" => "required|min:5"
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }

        if (Auth::attempt(request()->all())) {
            $token = auth()->user()->createToken("token")->plainTextToken;
            return response()->json([
                "message" => "Login Success",
                "user" => [
                    "name" => auth()->user()->name,
                    "email" => auth()->user()->email,
                    "accessToken" => $token,
                ]
            ], 200);
        }
        return response()->json(["message" => "Email or password incorrect"], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(["message" => "Logout success"], 200);
    }
}
