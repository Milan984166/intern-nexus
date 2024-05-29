<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\EmployerInfo;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected function redirectTo()
    {
            
        if (Auth::user()->role == '1') {

            return '/admin';
        } elseif(Auth::user()->role == '2') {

            return 'jobseeker/profile';
        }elseif(Auth::user()->role == '3') {

            return 'soletrader/profile';
        }elseif(Auth::user()->role == '4') {

            return 'company/profile';
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        
        $basename = pathinfo(url()->previous(),PATHINFO_BASENAME);

        if ($basename == 'job-seeker') {
            $data['role'] = 2;
        }

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'unique:users'],
            'role' => ['required',Rule::in([2,3,4])]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {      
        $data['status'] = 1;
        $basename = pathinfo(url()->previous(),PATHINFO_BASENAME);
        if ($basename == 'job-seeker') {
            $data['role'] = 2;
        }
        // dd($data);
        // echo "<pre>";
        // var_dump($data['role']);
        // exit();

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'role' => $data['role'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($user) && in_array($user->role, [3,4])) {

            $slug = EmployerInfo::createSlug($user->name);

            $employerInfoArray = ["organization_name" => $user->name, 
                                  "slug" => $slug, 
                                  "email" => $user->email,
                                  "phone" => $user->phone,
                                  "category_id" => 0
                                ];

            $user->employer_info()->create($employerInfoArray);
        }

        return $user;
    }
}
