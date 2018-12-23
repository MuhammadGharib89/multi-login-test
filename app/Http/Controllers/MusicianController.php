<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MusicianController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $email = Auth::guard('web')->user()->email;
        //Check if has another Roles
        $arr['roles'] = [];

        $member_exist = DB::table('members')->where('email', $email)->exists();

        $user_exist = DB::table('orch_officers')->where('email', $email)->exists();

        if ($member_exist) {
            array_push($arr['roles'], 'member');
        }

        if ($user_exist) {
            array_push($arr['roles'], 'officer');
        }

        return view('auth.musician-dashboard')->with($arr);
    }
}
