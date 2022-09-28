<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mail;
use App\Mail\SocialMail;
use App\Models\UserVerify;
use Config;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    { 
        $this->middleware('permission:sub-user-read|sub-user-write|sub-user-edit|sub-user-delete', ['only' => ['index','show','edit','delete','store','create','update']]);
    }
    
    /**
     * This function is helpful for display user's listing
     * *
     */
    public function index(Request $request)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //Pagination count from config file
        $items_per_page = Config::get('global.pagination.items_per_page');

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        if(Auth::user()->id == 1)
        {
            //  Get users data  
            $data = User::orderBy('id','DESC')->where('id','!=', Auth()->user()->id);
        }else{
            //  Get users data  
            $data = User::orderBy('id','DESC')->where('id','!=', Auth()->user()->id)->where('role_id' , '!=' , 1)->where('created_by' , Auth()->user()->id);
        }

        //Get Params of search
        $name = trim($request->query('name'));

        $email = trim($request->query('email'));

        if(!empty($name))
        {
            $data->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'like', '%' . $name . '%');
        }

        // Serach by email
        if(!empty($email))
        {
            $data = $data->where('email','LIKE','%'.$email.'%');
        }
       
        // Display user's data
        $data = $data->with('getRole')->paginate($items_per_page);
      
        // Redirect to view
        return view('admin.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * This function is helpful for display create user's form
     * *
     */
    public function create()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();
        
        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Get logged-in user details
        $loggedInUserData = Auth::user();

        // if($loggedInUserData->id == 1){
        //     // Get roles from role table
        //     $roleName = Role::where('id' , '!=', 1)->get();
        // } else{
            // if($loggedInUserData->id == 2){
                // User can add only those user whose role is assigned
                $roleName = Role::where('id','!=', $loggedInUserData->role_id)->where('created_by',$loggedInUserData->id)->get();
            // }
        // }
    
        // Redirect to view
        return view('admin.users.create', [ 'roleName' => $roleName]);
    }

    /**
     * This function is helpful for store's data in database
     * *
     */
    public function store(Request $request)
    {
        // Validations while creating user's form
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            'phone_number'     => 'required|numeric|digits:10',
            'address'   => 'required|min:1|max:120',
            'state'   => 'required|min:1|max:120',
            'city'   => 'required|min:1|max:120',
            'zip_code'   => 'required|numeric',
        ]);

        $request->request->add(['created_by' => Auth::user()->id]);

        $input = $request->all();

        // Create new user
        $user = User::create($input);

        // Generate random token
        $token = Str::random(64);

        // Save data on User-verify table
        UserVerify::create([
            'user_id' => $user->id, 
            'token' => $token
          ]);

        // Send Email to verify account
        Mail::send('emails.emailVerification', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });

        // Assign role to user
        $user->assignRole($request->input('role_id'));
    
        // Redirect to view
        return redirect()->route('users.index')->with('success','User Created Successfully');
    }

    /**
     * This function is helpful for show user's data
     * *
     */
    public function show($id)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Get user's details as per the specific id
        $user = User::with('getRole')->find($id);

        // Redirect to view
        return view('admin.users.show',compact('user'));
    }

   /**
     * This function is helpful for display edit form
     * *
     */
    public function edit($id)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Get id(which we need to edit)
        $user = User::where('role_id' , '!=' , 1)->find($id);
        $current_role_id=trim($user->role_id);
        
        // Get logged-in user details
        $loggedInUserData = Auth::user();

        // if($loggedInUserData->id == 1){
        //     // Get roles from role table
        //     $roleName = Role::where('id' , '!=', 1)->get();
        // } else{
        //     if($loggedInUserData->id  ==  2 ){
                // User can add only those user whose role is assigned
               
                $roleName = Role::where('id','!=', $loggedInUserData->role_id)->where('created_by',$loggedInUserData->id)->get();
        //     }
        // }
         
        // Redirect to view
        return view('admin.users.edit',compact('user','roleName','current_role_id'));
    }

    /**
     * This function is helpful for update user's data
     * *
     */
    public function update(Request $request, $id)
    {
        // Validations while updating user's form
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'phone_number'     => 'required|numeric|digits:10',
            'address'   => 'required|min:1|max:120',
            'state'   => 'required|min:1|max:120',
            'city'   => 'required|min:1|max:120',
            'zip_code'   => 'required|numeric',
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        // Find user's as per the id
        $user = User::find($id);

        // Update user's data
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('role_id'));
        
        // Redirect to route
        return redirect()->route('users.index')->with('success','User updated successfully');
    }

    /**
     * This function is helpful for delete(soft delete) user
     * *
     */
    public function destroy($id)
    {
        // Get user which need to delete
        $user = User::findOrFail($id);

        if($user->delete()){
            return redirect()->route('users.index')->with('error','User Deleted Successfully');
        } else{
            return back()->with('error','Oops! Something went wrong while updating data.'); // return back
        }
    }

    /**
     * This function is helpful verify user's account
     * *
     */
    public function verifyAccount(Request $request , $token)
    {
       
        // Get token from UserVerify table
        $verifyUser = UserVerify::orderBy('id', 'DESC')->where('token', $token)->first();
        // Check data exist or not
        if($verifyUser){
            $isToken = $verifyUser->is_token_expire;
            // If is_token_expire == yes then redirect on login page with error
            if($isToken == 'Yes'){
                return redirect()->route('login')->with('error' , 'This token is not valid');
            }
        }
        
        $success = 'Sorry your email cannot be identified.';
       
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
           
            // If email is not verifies
            if(!$user->email_verified_at) 
            {
               // After verified email, update 'email_verified_at' at user's table
                $verifyUser->user->email_verified_at = date('Y-m-d H:i:s');
                $verifyUser->user->save();

                // Display message i.e Email is verified
                $success = "Your e-mail is verified. Set Your Password For Exploring More.";

                // Encrypted id
                $encryptedId = base64_encode($user['id']);

                // Concatenate id with url
                $url = 'set-password/' . $encryptedId;

                return redirect($url)->with('success', $success);
            } else {
                // Display message when Email is alredy verified
                $success = "Your e-mail is already verified. Set Your Password.";
            }
        }
        return back()->with('error','Oops! Something went wrong while updating data.'); // return back
       
    }

    /**
     * This function is helpful for display set-password form
     * *
     */
    public function setPassword($id)
    {
        // Get id from url and decode it
        $encryptedId = base64_decode($id);

        // Get user's data as per the id
        $userEmail = User::where('id' , $encryptedId)->first();

        if($userEmail == ''){
            // return to view file
            return view('auth.passwords.setPassword', compact('userEmail'));
        }
        // return to view file
        return view('auth.passwords.setPassword', compact('userEmail'));
        
    }

    /**
     * This function is helpful for set password
     * *
     */
    public function setNewPassword(Request $request)
    {
        // Validations
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ],
        // Custom validations for password confirmation
        [
            'password_confirmation.same' => 'Password and Confirm-password are not same'
        ]);

        $user = User::where('email', $request->email)->first();
       
        // set password
        if ($user) {
            $user['password'] = Hash::make($request->password);
            $user['status'] = 'Active';
            $user->save();
    
            // Return to login 
            return redirect()->route('login')->with('success', 'Success! Your Password has been set successfully, You can now login');
        }

        // Return error
        else{
            return back()->with('error','Oops! Something went wrong while updating data.'); 
        }
    }

    /**
     * This function is helpful for resend email verification link
     * *
     */
    public function resendEmail($id)
    {
        // Get user's detais with id
        $user = User::where('id', $id)->first();
        $checkTokenExist = UserVerify::where('user_id' , $id)->where('is_token_expire' , 'no')->exists();

        if($checkTokenExist == true){
            UserVerify::where('user_id', $id)->update(['is_token_expire' => 'yes']);
        } 
       
        // Generate random token
        $token = Str::random(64);

        // Save data on User-verify table
        UserVerify::create([
            'user_id' => $user->id, 
            'token' => $token
          ]);

        // Send Email to verify account
        Mail::send('emails.emailVerification', ['token' => $token], function($message) use($user){
            $message->to($user->email);
            $message->subject('Email Verification Mail');
        });

        // Redirect to index section
        return redirect()->route('users.index')->with('success','Verification link send to users email');
    }

    /*
        ** This function implement to active/inactive user by Admin
    */
    public function updateStatus(Request $request)
    {
        $status = "Blocked";
        
        // If status is blocked
        if ($request->user_status=="Blocked") {
            // Update it to active
            $status="Active";
        
        } else{
            // If status is active
            $request->user_status == "Active";
            // Update it to blocked
            $status="Blocked";   
        }
        $form_data = [
            'status'  =>  $status
        ];

        try {
            // Find id whose status need to update
            $userUpdateStatus = User::find($request->id);

            // Update status
            $userUpdateStatus->status = $status;

            // Save value in database
            $userUpdateStatus->save();
        
            // Return response
            return response()->json(['id' => $request->id , 'form_data' => $form_data]);
        } catch (\Throwable $th) {
        }   
    }
}
