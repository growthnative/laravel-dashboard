<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;

/*
    |--------------------------------------------------------------------------
    | Change PasswordController
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for change password in Admin
    |
*/

class ChangePasswordController extends Controller
{
    /*
        ** This function implement to display create view of change password for admin
    */
    public function changePassword()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }
        return view('admin/password-change');
    }

    /*
        ** This function implement to change password
    */
    public function newPassword(Request $request)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }
        
        if(!(Hash::check($request->get('current_password'), Auth::user()->password))){
            // Display error message i.e password does not match
            return redirect()->back()->with("error","Please enter valid old password");
        }

        if(strcmp($request->get('current_password'), $request->get('password')) == 0) {
            // Display error message i.e current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password."); 
        }

        //validate password fields
        $request->validate([
            'current_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        if($user->save()) 
        {
            //password successfully changed
            return redirect()->back()->with("success", "Password successfully changed!");
        } else{
            // Display errpr message
            return redirect()->back()->with("error", "Opps! Something went wrong");
        }

    }
}
