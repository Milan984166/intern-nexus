<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App;
use App\User;

class LoginRegisterController extends Controller
{

    public function login(){
        
        if (!Auth::check()) {
            return view('login'); 
        }else{
            return redirect()->back()->with('error','Already Logged in!!');
        }

    }

    public function register(){
        
        if (!Auth::check()) {
            return view('register');
        }else{
            return redirect()->back()->with('error','Please log out to register New Account!!');
        }
    }

}
