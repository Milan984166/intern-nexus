<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\EmployerInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; 

class ApiAuthController extends Controller
{
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required'
        ]);

        if ($validator->fails())
        {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['status'] = 1;
        $user = User::create($request->toArray());
        
        $token = $user->createToken('Intern Nexus Register User')->accessToken;

        if (isset($user) && in_array($user->role, [3,4])) {

            $slug = EmployerInfo::createSlug($user->name);

            $employerInfoArray = ["organization_name" => $user->name, 
                                  "slug" => $slug, 
                                  "email" => $user->email,
                                  "phone" => $user->phone
                                ];

            $user->employer_info()->create($employerInfoArray);
        }

        $response = ['status' => 200, 'message' =>"successfully registered"];
        return response($response);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required'
        ]);
        if ($validator->fails())
        {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->role != $request->role) {

                $response = ['status' => 404, "message" =>'Role mismatch'];
                return response($response);

            }
            
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $user->token = $token;
                $response = ['status' => 200, 'message' => 'User Logged In!', 'data' =>['user' => $user]];
                return response($response);
            } else {
                $response = ["status" => 401, "message" => "Password mismatch"];
                return response($response);
            }
        } else {
            $response = ['status' => 404,"message" =>'User does not exist'];
            return response($response);
        }
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['status' => 200, 'message' => 'You have been successfully logged out!'];
        return response($response);
    }

    public function user(){
        $user = Auth::user();   
        return response()->json(['status' =>200, 'message' => "User Details", 'data' => $user]);
    }

}
