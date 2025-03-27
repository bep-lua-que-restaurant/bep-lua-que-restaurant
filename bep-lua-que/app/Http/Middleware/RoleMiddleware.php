<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để tiếp tục!');
        }
    
        // Kiểm tra quyền truy cập theo chức vụ
        if (!empty($roles) && !in_array(Auth::user()->chuc_vu_id, $roles)) {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập!');
        }
    
        return $next($request);
    }
    
    

    
}
