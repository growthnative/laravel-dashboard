<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    public function __construct()
    {
        //
    }

    public function checkUserStatus()
    {
        // Get user's data
        $getData =  User::find(auth()->user()->id);

        //Check admin verify user or not
        $adminVerify = $getData['status'];
    
        if($adminVerify != 'Active'){
            Session::flush();
            Auth::logout();
            
            // Display error message
            return true;
        } else{
            return false;
        }
    }

   
}
