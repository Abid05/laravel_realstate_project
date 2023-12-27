<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard(){

        return view('admin.index');
    }

    //admin logout
    public function AdminLogout(Request $request){

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin-login');
    }

    //admin login
    public function AdminLogin(Request $request){

        return view('admin.admin_login');
    }
}
