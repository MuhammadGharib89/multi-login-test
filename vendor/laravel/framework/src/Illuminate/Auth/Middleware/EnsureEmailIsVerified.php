<?php

namespace Illuminate\Auth\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Auth;
class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
    public function handle($request, Closure $next, $guard = null)
    {
    
        $guards = array_keys(config('auth.guards'));
    
        foreach($guards as $guard) {
    
            if ($guard == 'officer') {
    
                if (Auth::guard($guard)->check()) {
    
                    if (! Auth::guard($guard)->user() ||
                        (Auth::guard($guard)->user() instanceof MustVerifyEmail &&
                        ! Auth::guard($guard)->user()->hasVerifiedEmail())) {
                        return $request->expectsJson()
                                ? abort(403, 'Your email address is not verified.')
                                : Redirect::route('officer.verification.notice');
                    }  
    
                }
    
            }
    
            elseif ($guard == 'member') {
    
                if (Auth::guard($guard)->check()) {
    
                    if (! Auth::guard($guard)->user() ||
                        (Auth::guard($guard)->user() instanceof MustVerifyEmail &&
                        ! Auth::guard($guard)->user()->hasVerifiedEmail())) {
                        return $request->expectsJson()
                                ? abort(403, 'Your email address is not verified.')
                                : Redirect::route('member.verification.notice');
                    }  
    
                }
    
            }
    
            elseif ($guard == 'web') {
    
                if (Auth::guard($guard)->check()) {
    
                    if (! Auth::guard($guard)->user() ||
                        (Auth::guard($guard)->user() instanceof MustVerifyEmail &&
                        ! Auth::guard($guard)->user()->hasVerifiedEmail())) {
                        return $request->expectsJson()
                                ? abort(403, 'Your email address is not verified.')
                                : Redirect::route('musician.verification.notice');
                        }  
    
                }
            }
    
        }
    
        //return $next($request);
    }
    

}
