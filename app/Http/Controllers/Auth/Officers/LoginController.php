<?php

namespace App\Http\Controllers\Auth\Officers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Auth;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/officer';
    protected $guard = 'officer';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest:officer')->except('logout'); 
    }

    public function showLoginForm()
    {
        return view('auth.officer-login');
    }

    public function login(Request $request)
    {
        //Validate Incoming Requests
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        //Attempt to Login
        if(Auth::guard('officer')->attempt(['email'=>$request->email,'password'=>$request->password ], $request->remember))
        {
        //successful redirect

            return redirect()->intended(route('officer.dashboard'));
        }

        //unsuccessful redirect
        return redirect()->back()->withInput($request->only('email', 'remember'));

    }

    public function Logout()
    {
        Auth::guard('officer')->logout();

        return redirect()->route('officer.login');
    }
}
