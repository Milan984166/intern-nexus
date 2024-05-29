<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users',array('users' => $users, 'id' => 0));
    }

    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:225|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required'],
        ]);

        $request['status'] = isset($request['status']) ? $request['status'] : 0;
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => $request['role'],
            'status' => $request['status'],
            'phone' => $request['phone'],
            'current_address' => $request['current_address'],
            'permanent_address' => $request['permanent_address'],
            'gender' => $request['gender'],
            'dob' => $request['dob'],
            'religion' => $request['religion'],
            'maritial_status' => $request['maritial_status'],
            'nationality' => $request['nationality']
        ]);

        return redirect()->to('admin/users')->with('status', 'User added Successfully!');

    }

    public function edit($id)
    {
        $id = base64_decode($id);
       	$user = User::findOrFail($id);
       	return view('admin.users',array('user' => $user, 'id' => $id));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);
        $request['status'] = isset($request['status']) ? $request['status'] : '0';
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => [
                    'required',
                    Rule::unique('users')->ignore($user->id)
                    ],
        ]);
        $user->name = $request['name'];
        $user->role = $request['role'];
        $user->status = $request['status'];
        $user->phone = $request['phone'];
        $user->dob = $request['dob'];
        $user->current_address = $request['current_address'];
        $user->permanent_address = $request['permanent_address'];
        $user->religion = $request['religion'];
        $user->nationality = $request['nationality'];
        $user->gender = $request['gender'];
        $user->maritial_status = $request['maritial_status'];

        $user->save();
        return redirect('admin/users')->with('status', 'User Updated Successfully!');

    }

    public function delete($id)
    {

        $user = User::where('id', $id)->firstOrFail();
        if ($user) {
            $user->delete();
            return redirect()->back()->with('status', 'User Deleted Successfully!');
            
        }
        return redirect()->back()->with('status', 'Something Went Wrong!');

    }

    public function change_password(Request $request)
    {
        
       $user = User::find($request->id);

        if (Hash::check($request->oldpassword, $user->password)) {

            $validatedData = $request->validate([
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            $user->password = Hash::make($request['password']);
            $user->save();
            return redirect()->back()
                ->with('status', 'Password updated Successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Your Current Password Doesnot Match!!');
        }

    }

}
