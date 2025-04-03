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
        if (!Auth::check()) {
            if (!$request->routeIs('login')) {
                return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để tiếp tục!');
            }
            return $next($request);
        }

        // Nếu đã đăng nhập nhưng không có quyền, chuyển hướng về trang dashboard
        if (!empty($roles) && !in_array(Auth::user()->chuc_vu_id, $roles)) {
            if (!$request->routeIs('admin.dashboard')) {
                return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập!');
            }
        }

        return $next($request);
    }
    }
    
    

    

