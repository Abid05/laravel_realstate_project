<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    //admin profile
    public function AdminProfile(){

        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view',compact('profileData'));
    }
    //Admin store
    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notifiaction =['messege'=>'Updated Successfully!','alert-type'=>'success'];
        return redirect()->back()->with($notifiaction);

    }
    //Admin change password
    public function AdminChangePassword(){

        $id = Auth::user()->id;
        $profileData = User::find($id);  
        return view('admin.admin_change_password',compact('profileData'));
    }
    //admin update password
    public function AdminUpdatePassword(Request $request){
        //validation
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $current_pass = Auth::user()->password;
        $old_pass = $request->old_password;
        $new_pass = $request->new_password;

        if(Hash::check($old_pass,$current_pass)){
            $user = User::find(Auth::id());
            $user->password=Hash::make($new_pass);
            $user->save();
            $notifiaction =['messege'=>'Your Password Changed!','alert-type'=>'success'];
            return redirect()->back()->with($notifiaction);
        }else{

            $notifiaction =['messege'=>'Old Password Not Matched!','alert-type'=>'error'];
            return redirect()->back()->with($notifiaction);
        }
    }
}
