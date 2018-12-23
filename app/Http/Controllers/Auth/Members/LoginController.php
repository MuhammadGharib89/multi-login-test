<?php

namespace App\Http\Controllers\Auth\Members;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/member';
    protected $guard = 'member';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest:member')->except('logout'); 
    }

    public function showLoginForm()
    {
        return view('auth.member-login');
    }

    public function login(Request $request)
    {
        //Validate Incoming Requests
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        //Attempt to Login
        if(Auth::guard('member')->attempt(['email'=>$request->email,'password'=>$request->password ], $request->remember))
        {
        //successful redirect

            return redirect()->intended(route('member.dashboard'));
        }

        //unsuccessful redirect
        return redirect()->back()->withInput($request->only('email', 'remember'));

    }

    public function Logout()
    {
        Auth::guard('member')->logout();

        //$request->session()->invalidate();
        //$this->loggedOut($request) ?: 
        return redirect()->route('member.login');
    }

}
