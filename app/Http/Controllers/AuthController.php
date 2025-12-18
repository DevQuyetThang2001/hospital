<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\New_;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => "Email không được để trống",
            'password.required' => "Mật khẩu không được để trống"
        ]);



        if (Auth::attempt($user)) {

            $request->session()->regenerate();
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.index');
                case 'schedule_manager':
                    return redirect()->route('manager.index');
                case 'doctor':
                    return redirect()->route('doctor.index');
                case 'receptionist':
                    return redirect()->route('receptionist.index');
                default:
                    return redirect()->route('home');
            }
            // $request->session()->regenerate();
            // return redirect()->intended('/dashboard');
        }

        return back()->with('msg', 'Thông tin đăng nhập không chính xác');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('msg', 'Bạn đã đăng xuất thành công');
    }


    public function create()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {

        $user = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'CCCD' => 'required|digits:12|unique:users,CCCD',
        ], [
            'name.required' => "Tên tài khoản không được để trống",
            'name.max' => 'Tên tài khoản chỉ được tối đa 100 ký tự',
            'email.required' => "Email không được để trống",
            'email.email' => "Email phải hợp lệ",
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải nhiều hơn 5 ký tự',
            'CCCD.required' => 'Căn cước công dân không được để trống',
            'CCCD.digits' => 'Căn cước công dân phải đủ 12 chữ số',
            'CCCD.unique' => 'Căn cước công dân đã tồn tại trong hệ thống'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->CCCD = $request->CCCD;

        $user->save();

        return redirect()->route('login')->with('msg', 'Bạn vừa tạo tài khoản thành công');
    }
}
