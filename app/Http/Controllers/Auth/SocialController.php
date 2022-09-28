<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\SocialMail;
use Illuminate\Support\Str;
use App\Services\SocialFacebookAccountService;

/*
    |--------------------------------------------------------------------------
    | Social Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for login user with social(like gmail,facebook etc)
    |
*/
class SocialController extends Controller
{
    /**
     * Create a redirect method to google api.
     *
     * @return void
    */
    public function redirectToGoogle()
    {
        //Redirect to google
        return Socialite::driver('google')->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
      
            // Find user
            $finduser = User::where('email' , $user->email)->first();
      
            // If user already exists
            if($finduser){
                Auth::login($finduser);

                // Update existing user
                $newUser = User::updateOrCreate([
                    'id' => $finduser->id
                ],[
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=> $user->id,
                    'social_type'=> 'google',
                ]);
                return redirect('/admin/welcome');
      
            }
            // Create new user
            else{
                // System generated password
                $password = Str::random(8);

                // Make password as a hash password
                $systemGeneratedPassword = Hash::make($password);
               
                // Save data on database
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=> $user->id,
                    'social_type'=> 'google',
                    'password' => $systemGeneratedPassword,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'role_id' => 1,
                ]);

                // Assign role to user
                $newUser->assignRole(1);
     
                Auth::login($newUser);

                // Data send to email template
                $mailData = [
                    'body' => 'This is for testing email using smtp.',
                    'email' => $newUser['email'],
                    'password' => $password
                ];
                 
                // Send  mail after creating user
                Mail::to($newUser['email'])->send(new SocialMail($mailData));
      
                return redirect('/admin/welcome');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
   * Create a redirect method to facebook api.
   *
   * @return void
   */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
    */
    public function handleFacebookCallback()
    {
        {
            try {
                $user = Socialite::driver('facebook')->user();

                $finduser = User::where('social_id', $user->id)->first();

                if($finduser){
                    Auth::login($finduser);
                    return redirect('/home');
                }else{
                    $newUser = User::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'social_id'=> $user->id,
                        'social_type' => 'facebook',
                        'password' => encrypt('123456dummy')
                    ]);
                    Auth::login($newUser);
                    return redirect()->route('home');
                }
        
            }catch (Exception $e) {
                dd($e->getMessage());
            }
        }
    }
}
