<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthController extends Controller
{
    public function register(Request $request) {
        $field = $request->validate([
            'name'    =>  'required|string',
            'email' =>  'required|string|unique:users,email',
            'password'  =>  'required|string|confirmed'
        ]);

        $user = User::create([
            'name'    =>  $field['name'],
            'email' =>  $field['email'],
            'user_type'  => $field['user_type'],
            'password'  =>  bcrypt($field['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response   =   [
            'user'  =>  $user,
            'token' =>  $token,
            'message' => 'Created Successfully'
        ];

        return response($response, 201);
        
    }

    public function login(Request $request) {
        $field = $request->validate([
            'email' =>  'required|string',
            'password'  =>  'required|string'
        ]);

        // Check Email
        $user = User::where('email', $field['email'])->first();
        // Check Password
        if(!$user || !Hash::check($field['password'], $user->password)) {
            return response(
                [
                    'message'   => 'Wrong Email or Password'
                ],
                401
            );
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response   =   [
            'user'  =>  $user,
            'token' =>  $token,
            'message' => 'Login Successful'
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response ([
            'message'   =>  'Logged out'
        ]);
    }
}
