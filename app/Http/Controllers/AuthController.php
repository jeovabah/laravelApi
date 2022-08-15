<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $fields = $request-> validate([
                'name' => "required|string",
                'email' => "required|string|unique:users,email",
                'password' => "required|confirmed"
             ]);
                 
             $user = User::create([
                 'name' => $fields['name'],
                 'email' => $fields['email'],
                 'password' => bcrypt($fields['password']),
             ]);
             
             $token = $user->createToken('myapptoken')->plainTextToken;
     
             $response = [
                 'user' => $user,
                 "token" => $token,
             ];
     
             return response($response, 201);
        } catch (\Exception $e) {
            return response(["message" => $e->getMessage()], 500);
        }
    }
    public function login (Request $request) {
        try {
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                return response(["message" => "User not found"], 404);
            }
            if (!password_verify($request->input('password'), $user->password)) {
                return response(["message" => "Invalid credentials"], 401);
            }
            
            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                'user' => $user,
                "token" => $token,
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            return response(["message" => $e->getMessage()], 500);
        }
    }
}
