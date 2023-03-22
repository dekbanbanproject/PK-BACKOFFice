<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $res)
    {
        $input = $res->all();

        $this->validate($res,[
            // 'email' => 'required|email',
            'username' => 'required',
            'password' => 'required'
        ]);
        if (auth()->attempt(array('username'=>$input['username'],'password'=>$input['password']))) {
           if (auth()->user()->type == 'ADMIN') {
               return redirect()->route('admin.home');
            } elseif(auth()->user()->type == 'STAFF') {
                return redirect()->route('staff.home');
            } elseif(auth()->user()->type == 'MANAGE') {
                return redirect()->route('manage.home');     
            } elseif(auth()->user()->type == 'USER') {
                return redirect()->route('user.home');  
            } elseif(auth()->user()->type == 'RPST') {
                return redirect()->route('rpst.home_rpst');         
           } else {
                return redirect()->route('home');
           }
        }else{
            return redirect()->route('login')->with('error','username and password Incorrect');
        }       
    }
}
