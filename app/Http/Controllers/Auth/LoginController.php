<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function handleProviderCallback($service)
    {
        if($service == 'twitter') {
            $user = Socialite::driver($service)->user();
        }else {
            $user = Socialite::driver($service)->stateless()->user();
        }

        $findUser = User::where('email', $user->user['email'])->first();
        if($findUser){
            Auth::login($findUser);
        }else {
            $newUser = new User;
            $newUser->email = $user->user['email'];
            $newUser->name = $user->user['name'];
            $newUser->password = bcrypt(mt_rand(10000000,99999999));
            $newUser->save();
            Auth::login($newUser);
        }
        return redirect('home');
    }
}
