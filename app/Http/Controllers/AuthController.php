<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        // --------  validate on the requested data   ------------- \\

        $fields = Validator::make($request->all(), [
            'name' => 'string|required',
            'email' => 'string|required|unique:users',
            'password' => 'string|required|confirmed',
        ]);

        // --------  check if the data is correct  ------------- \\
        if ($fields->fails()) {
            return new JsonResponse(['success' => false, 'message' => $fields->errors()], 422);
        }

        // --------  create user in database  ------------- \\

        $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
        ]);


        // Send verification email
        if ($user) {
            $verify2 =  DB::table('password_reset_tokens')->where([
                ['email', $request->email]
            ]);

            if ($verify2->exists()) {
                $verify2->delete();
            }
            $pin = rand(100000, 999999);
            DB::table('password_reset_tokens')
                ->insert(
                    [
                        'email' => $request->email,
                        'token' => $pin
                    ]
                );
        }

        Mail::to($request->email)->send(new VerifyEmail($pin));


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
        return new JsonResponse(
            [
                'success' => true,
                'data' => $response,
                'message' => 'Successful created user. Please check your email for a 6-digit pin to verify your email.',
            ],
            201,
            [
                'Accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        );
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


        //TODO make user take one token when logging, just one login in time

        // --------  check email in database  ------------- \\
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }


        // ---------  create token for the user and save it  --------- \\
        // Check if the user has a personal access token
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
