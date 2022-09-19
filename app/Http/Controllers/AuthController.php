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
    public function login(Request $request) {
        try {
            $fields = $request-> validate([
                'email' => "required|string",
                'password' => "required|string"
             ]);
             
             if (!$token = auth()->attempt($fields)) {
                 return response(["message" => "Invalid Credentials"], 401);
             }
             
            $user = User::where('email', $fields['email'])->first();
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
