<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        // --------  validate on the requested data   ------------- \\

        $fields = $request->validate([
            'name' => 'string|required',
            'email' => 'string|required|unique:users',
            'password' => 'string|required|confirmed',
        ]);

        // --------  create user in database  ------------- \\

        $user = User::create([
           'name' => $fields['name'],
           'email' => $fields['email'],
           'password' => bcrypt($fields['password']),
        ]);

        // ---------  create token for the registered user and save it  --------- \\
        $token = $user->createToken('my_token')->plainTextToken;


        // ---------  return with custom attributes  --------- \\
        $response = [
            'id,' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token
        ];

        // ---------  return data and add headers  --------- \\
        return response($response,
            201,
            [
                'Accept' => 'application/json',
                'content-type' => 'application/json',
            ]);
    }
    public function login(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        // --------  validate on the requested data   ------------- \\

        $fields = $request->validate([
            'email' => 'string|required',
            'password' => 'string|required',
        ]);

        // --------  check email in database  ------------- \\
        $user = User::where('email',$fields['email'])->first();


        // --------  check email in database  ------------- \\
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }


        // ---------  create token for the user and save it  --------- \\
        $token = $user->createToken('my_token')->plainTextToken;


        // ---------  return with custom attributes  --------- \\
        $response = [
            'id,' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token
        ];


        // ---------  return data and add headers  --------- \\
        return response($response,
            201,
            [
                'Accept' => 'application/json',
                'content-type' => 'application/json',
            ]);
    }
    public function logout(): array
    {
        // -------------  delete user tokens  ------------ \\
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
