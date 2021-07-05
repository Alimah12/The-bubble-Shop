<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'phonenumber' => 'required|string|unique:users,phonenumber',
            'address' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'phonenumber' => $fields['phonenumber'],
            'address' => $fields['address'],
            'password' => Hash::make($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);


    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        // console.log(auth()->user());
        return[

            'message' => 'Logged Out and Token Deleted!'
        ];

        
    }
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();
        // console.log($user);
        // print_r($user->password);
        // print_r($fields['password']);
        // print_r(Hash::make('password3'));
        // print_r($user->password);
        // dd(Hash::check('password3',$user->password));
        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'No match'
            ], 401);
        }
        // print_r(Hash::check($fields['password'], Hash::make('password3')));
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    
    }
}
