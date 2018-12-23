<?php

namespace App\Http\Controllers\Auth\Officers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

use Auth;
class VerificationController extends Controller
{

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth:officer');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $arr['roles'] = $this->getRoles();
        return Auth::guard('officer')->user()->hasVerifiedEmail()
                        ? redirect(route('officer.dashboard'))
                        : view('auth.officer-verify')->with($arr);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    /*
    public function verify(Request $request)
    {
        if ($request->route('id') != Auth::guard('officers')->user()->getKey()) {
            throw new AuthorizationException;
        }

        if (Auth::guard('officer')->user()->markEmailAsVerified()) {
            event(new Verified(Auth::guard('officer')->user()));
        }

        return redirect(route('officer.dashboard'))->with('verified', true);
    }
    */
    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
     /*
    public function resend(Request $request)
    {
        if (Auth::guard('officer')->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        Auth::guard('officer')->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
    */

    public function getRoles()
    {
        $email = Auth::guard('officer')->user()->email;
        //Check if has another Roles
        $roles = [];

        $member_exist = DB::table('members')->where('email', $email)->exists();

        $user_exist = DB::table('users')->where('email', $email)->exists();

        if ($member_exist) {
            array_push($roles, 'member');
        }

        if ($user_exist) {
            array_push($roles, 'musician');
        }
        return $roles;
        
    }


}
