<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['message' => $validator->errors()]);
        }
        $select = DB::table('password_reset_tokens')
            ->where('email', Auth::user()->email)
            ->where('token', $request->token);

        if ($select->get()->isEmpty()) {
            return new JsonResponse(['success' => false, 'message' => "Invalid PIN"], 400);
        }

        $select = DB::table('password_reset_tokens')
            ->where('email', Auth::user()->email)
            ->where('token', $request->token)
            ->delete();

        $user = User::find(Auth::user()->id);
        $user->email_verified_at = Carbon::now()->getTimestamp();
        $user->save();

        return new JsonResponse(['success' => true, 'message' => "Email is verified"], 200);
    }
}
