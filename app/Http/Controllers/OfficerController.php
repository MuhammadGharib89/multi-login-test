<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;

class OfficerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:officer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $arr['roles'] = $this->getRoles();
        return view('auth.officer-dashboard')->with($arr);
    }

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
