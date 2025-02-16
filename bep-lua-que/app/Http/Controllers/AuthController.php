<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NhanVien;

class AuthController extends Controller
{
    // Hiển thị trang đăng nhập
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();



            $user = Auth::user();
            switch ($user->chuc_vu_id) {
                case 2:
                    return redirect()->route('bep.dashboard');
                case 3:
                    return redirect()->route('thungan.dashboard');
                case 4:
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect()->route('login')->with('error', 'Chức vụ không hợp lệ!');
            }
        }

        return back()->with('error', 'Email hoặc mật khẩu không đúng!');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    }
}
