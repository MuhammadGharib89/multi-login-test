<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use App\OrchOfficer as OrchOfficer;
use App\User as User;
use App\Member as Member;
use Auth;
trait VerifiesEmails
{
    use RedirectsUsers;

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
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
        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException;
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }
    */
    public function verify(Request $request)
    {
        //find email and id
        $music_exist = DB::table('users')->where('id',$request->id)->where('email',$request->email)->exists();
        if ($music_exist) 
        {
            $musician = User::find($request->id);

            if ($musician->markEmailAsVerified()) {
                event(new Verified($musician));
            }
            return redirect(route('musician.dashboard'))->with('verified', true);

        }

        $officer_exist = DB::table('orch_officers')->where('id',$request->id)->where('email',$request->email)->exists();
        if ($officer_exist) 
        {
            $officer = OrchOfficer::find($request->id);
            if ($officer->markEmailAsVerified()) {
                event(new Verified($officer));
            }

            return redirect(route('officer.dashboard'))->with('verified', true);

        }
        $member_exist = DB::table('members')->where('id',$request->id)->where('email',$request->email)->exists();
        if ($member_exist) 
        {
            if (Auth::guard('member')->user()->markEmailAsVerified()) {
                event(new Verified(Auth::guard('member')->user()));
            }

            return redirect(route('member.dashboard'))->with('verified', true);

        }

        /* old with role doesn't work
            if ($request->role == 'musician') {
                    
                if ($request->route('id') != Auth::guard('web')->user()->getKey()) {
                    throw new AuthorizationException;
                }

                if (Auth::guard('web')->user()->markEmailAsVerified()) {
                    event(new Verified(Auth::guard('web')->user()));
                }

                return redirect(route('musician.dashboard'))->with('verified', true);

            }elseif ($request->role == 'officer') {
                if ($request->route('id') != Auth::guard('officer')->user()->getKey()) {
                    throw new AuthorizationException;
                }

                if (Auth::guard('officer')->user()->markEmailAsVerified()) {
                    event(new Verified(Auth::guard('officer')->user()));
                }

                return redirect(route('officer.dashboard'))->with('verified', true);

            }elseif ($request->role == 'member') {

                if ($request->route('id') != Auth::guard('member')->user()->getKey()) {
                    throw new AuthorizationException;
                }

                if (Auth::guard('member')->user()->markEmailAsVerified()) {
                    event(new Verified(Auth::guard('member')->user()));
                }

                return redirect(route('member.dashboard'))->with('verified', true);
            }

        */

    }


    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
