<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for updating profile's data
    |
    */

    /**
     * Render/Display profile page.
     *
     * @return void
    */
    public function create()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Get logged-in user
        $user = User::find(auth()->user()->id);

        // get name of user
        $name = explode(' ', trim($user->first_name));

        $firstWord = $name[0];

        // Get first alphabet of name
        $firstName = mb_substr($firstWord[0], 0, 1);

        // Return to profile page
        return view('admin.profile' , compact('user' , 'firstName'));
    }

    /**
     * Update profile page data.
     *
     * @return void
    */
    public function store(Request $request)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }
        
        // Find logged-in user id
        $loggedInUser = Auth()->user()->id;
        
        // validation for profile form
        $validator = Validator::make($request->all(), [
            'first_name'          => 'required|min:1|max:120',
            'last_name'          => 'required|min:1|max:120',
            'email'         => 'required|min:1|max:120|unique:users,email,' . $loggedInUser,
            'phone_number'     => 'required|numeric|digits:10',
            'address'   => 'required|min:1|max:120',
            'state'   => 'required|min:1|max:120',
            'city'   => 'required|min:1|max:120',
            'zip_code'   => 'required|numeric',
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ],
        // custom message for image
        [
            'profile_image.image' => 'Only jpeg, jpg and formats are allowed'
        ]);

        // if validations are fails then display error
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // For image upload in profile section
        if($request->profile_image){
            $imageName = time() . '.' . $request->profile_image->extension();

            $request->profile_image->move(public_path('images/profile'), $imageName);

            // Store profile image on database and also in profile folder
            $data = $request->all();
            $data['profile_image'] = $imageName;

            // Update/create profile data with image
            $user = User::updateOrCreate(['id' => auth()->user()->id], $data);
            return redirect('profile/create')->with('success', 'Profile Information Updated Successfully');
        }else{
            // Get all entered data 
            $data = $request->all();

            // Save/update pprofile data on database
            $user = User::updateOrCreate(['id' => auth()->user()->id], $data);

            // After save data redirect on profile create page
            return redirect('profile/create')->with('success', 'Profile Information Updated Successfully');
        } 
    }
}
