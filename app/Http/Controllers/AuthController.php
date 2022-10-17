<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $data = $request->validate([
            'name'=>'required|string',
            'phone'=>['required', 'regex:/^(\+7|8)?[0-9]{10}$/'],
            'email'=>'required|string|email|unique:users',
            'password'=>'required|confirmed|min:8'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Вышли из аккаунта.'
        ]);
    }

    public function login(Request $request){

        $data = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);

        $user = User::where('email', $data['email'])->first();

        if(!$user){
            return response()->json([
                'message'=>'Неверный email.'
            ], 401);
        } elseif (!Hash::check($data['password'], $user->password)){
            return response()->json([
                'message'=>'Неверный пароль.'
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
