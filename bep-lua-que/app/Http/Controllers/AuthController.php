<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;

class AuthController extends Controller
{
    // Hiển thị trang đăng nhập
    // public function showLoginForm()
    // {
    //     return view('admin.login');
    // }

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();



    //         $user = Auth::user();
    //         switch ($user->chuc_vu_id) {
    //             case 2:
    //                 return redirect()->route('bep.dashboard');
    //             case 3:
    //                 return redirect()->route('thungan.dashboard');
    //             case 4:
    //                 return redirect()->route('admin.dashboard');
    //             default:
    //                 return redirect()->route('login')->with('error', 'Chức vụ không hợp lệ!');
    //         }
    //     }

    //     return back()->with('error', 'Email hoặc mật khẩu không đúng!');
    // }


    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    // }


    // Hiển thị trang đăng nhập
    public function showLoginForm()
    {
        return view('admin.login');
    }
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);
        
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user(); // Lấy thông tin user từ bảng nhan_viens
            
            // Điều hướng dựa theo role của user
            if ($user->chuc_vu_id == 1) {
                return redirect()->route('dat-ban.index')->with('success', 'Đăng nhập thành công!');
            }
            if ($user->chuc_vu_id == 2) {
                return redirect()->route('bep.dashboard')->with('success', 'Đăng nhập thành công!');
            }
            if ($user->chuc_vu_id == 3) {
                return redirect()->route('thungan.getBanAn')->with('success', 'Đăng nhập thành công!');
            }
    
            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
        }
    
        return back()->with('error', 'Email hoặc mật khẩu không đúng!')->withInput();
    }
    
    public function logout(Request $request)
{
    // Xóa toàn bộ session của người dùng
    Session::flush();

    // Đăng xuất người dùng
    Auth::logout();

    // Chuyển hướng về trang đăng nhập
    return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công!');
}

}
