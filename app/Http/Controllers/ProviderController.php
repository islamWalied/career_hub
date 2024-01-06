<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }
    public function callback($provider)
    {
        try {
            $SocialUser = Socialite::driver($provider)->stateless()->user();

            if (User::where('email',$SocialUser->getEmail())->exists()){
                return  redirect('/api/login')->withErrors(['email','use different email as this email is a used email']);
            }

            $user = User::where([
                'provider' => $provider,
                'provider_id' => $SocialUser->id
            ])->first();


            if (!$user)
            {
                $user = User::create([
                    'name' => $SocialUser->getName(),
                    'email' => $SocialUser->getEmail(),
                    'password' => Hash::make($SocialUser->password),
                    'email_verified_at' => now(),
                    'provider' => $provider,
                    'provider_id' => $SocialUser->getId(),
                    'provider_token' => $SocialUser->token,
                ]);
            }
            Auth::login($user);
            return redirect('/');
        }
        catch (\Exception $e){
            return redirect('/api/login');
        }
    }
}
