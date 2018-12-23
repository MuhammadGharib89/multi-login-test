<?php

namespace App\Http\Controllers\Auth\Officers;

use App\OrchOfficer;
use App\Member;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Auth;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/officer';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:officer');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('officer');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.officer-register');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:orch_officers'],
            'surname'=> ['required', 'string'],
            'orchestra_name'=> ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'gender' => [
                'required', 'string',
                Rule::in(['male', 'female']),
            ],
        
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\OrchOfficer
     */
    protected function create(array $data)
    {
        return OrchOfficer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'surname' => $data['surname'],
            'orchestra_name' => $data['orchestra_name'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $email = $request->email; 
        $pass = $request->password; 

        event(new Registered($user = $this->create($request->all())));
        
        $this->updatePass($email, $pass);
        
        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect(route('officer.dashboard'));
    }

    //Update Passwords if there are found in other guards
    public function updatePass($email, $pass){

        $member_exist = DB::table('members')->where('email', $email)->exists();

        $user_exist = DB::table('users')->where('email', $email)->exists();

        if ($member_exist) {
            Member::where('email', $email)->update([ 'password' => Hash::make($pass) ]);
        }

        if ($user_exist) {
            User::where('email', $email)->update([ 'password' => Hash::make($pass) ]);
        }

    }


}
