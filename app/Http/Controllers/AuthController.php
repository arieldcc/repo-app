<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login() {
        // dd(Auth::user());
        // dd(Hash::make(123456));
        if(!empty(Auth::check())){
            return redirect('dashboard/repodashboard');
        }
        return view('auth.login');
    }

    public function auth_login(Request $request){
        $remember = !empty($request->remember) ? true : false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)){
            return redirect('dashboard/repodashboard');
        }else{
            return redirect()->back()->with('error', 'Please enter current email and password');
        }
    }

    public function logout(){
        Auth::logout();

        return redirect(url(''));
    }
}
