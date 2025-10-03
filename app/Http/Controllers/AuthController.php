<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\New_;

class AuthController extends Controller
{
    public function index(){
        return view('admin.auth.login');
    }

    public function login(Request $request){
        $user = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ],[
            'email.required' => "Email không được để trống",
            'password.required' => "Mật khẩu không được để trống"
        ]);



        if(Auth::attempt($user)){

            $request->session()->regenerate();
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.index');
                case 'doctor':
                    return redirect()->route('doctor.index');
                default:
                    return redirect()->route('home');
            }
            // $request->session()->regenerate();
            // return redirect()->intended('/dashboard');
        }

        return back()->with('msg','Thông tin đăng nhập không chính xác');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('msg', 'Bạn đã đăng xuất thành công');
    }


    public function create(){
        return view('admin.auth.register');
    }

    public function register(Request $request){
        
        $user = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'password' => 'required|min:5'
        ],[
            'name.required' => "Tên tài khoản không được để trống",
            'name.max' => 'Tên tài khoản chỉ được tối đa 100 ký tự',
            'email.required' => "Email không được để trống",
            'email.email' => "Email phải hợp lệ",
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải nhiều hơn 5 ký tự' 
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('login')->with('msg','Bạn vừa tạo tài khoản thành công');
 
    }
}
