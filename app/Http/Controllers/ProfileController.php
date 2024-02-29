<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function UserProfile()
    {
        $user_id = Auth::user()->id;
        $user = Profile::where('user_id',$user_id)->first();
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $user = Auth::user();
        $request->validated();
        $image = $user->profile->image;
        if ($request->hasFile('image'))
        {
            $image = $request->file('image')->store('profiles','public');
        }
        $user->profile->fill([
            'user_id' => Auth::user()->id,
            'name' =>$request->name ?? $user->name,
            'job_title' =>$request->job_title ?? $user->job_title,
            'phone' =>$request->phone ?? $user->phone,
            'image' =>$image,

        ])->save();
        return response()->json([
            'message' => 'the user profile has been updated successfully',
            'user' => $user->profile
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
