<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /** AdminController.php
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    { 
        $this->middleware('permission:dashboard-read|dashboard-write|dashboard-edit|dashboard-delete', ['only' => ['welcome','dashboard']]);
    }
    
    /**
     * Admin welcome resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Reterive the currently authenticated user
        $loggedUser = Auth::user();

        // Get name of user
        $name = explode(' ', trim($loggedUser->first_name));
    
        $firstWord = $name[0];

        // Get first alphabet of name
        $firstName = mb_substr($firstWord[0], 0, 1);

        // Redirect to view
        return view('admin.welcome' , compact('loggedUser' , 'firstName'));
    }

    /**
     * Admin Dashboard resource
     * 
     */
    public function dashboard()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }
        
        // Retrieve the currently authenticated user
        $userID = Auth::user()->id;  

        // if user is Admin
        if(Auth()->user()->id == 1){
            $countUser = User::where('role_id' , '!=' , 1)->count();
        }
        else{
            //if user is rather than admin
            $countUser = User::where('role_id' , '!=' , 1)->where('created_by' , $userID)->count();
        }

        // Retrieve role from role table
        $totalRole = Role::where('created_by',$userID)->count();

        // Return to view
        return view('admin.dashboard', ['countUser'=>$countUser , 'totalRole' => $totalRole]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        die('index');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
