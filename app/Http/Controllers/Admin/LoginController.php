<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
    	echo "string";
    	exit();
    	if (!Auth::check()) {
			return view('admin/login');	
		}else{
			return redirect('/admin');
		}
    }
}
