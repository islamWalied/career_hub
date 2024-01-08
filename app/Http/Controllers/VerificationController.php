<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = \App\Models\User::where('email',$request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return response()->json(['message' => 'Email verified'], 200);
        } else {
            return response()->json(['message' => 'Unable to verify email'], 500);
        }
    }
}
