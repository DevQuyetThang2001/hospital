<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {

        // Nếu chưa đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $userRole = Auth::user()->role;

        // Nếu role khớp → cho đi tiếp
        if ($userRole === $role) {
            return $next($request);
        }

        // Nếu role không khớp → chuyển đến dashboard đúng role
        switch ($userRole) {
            case 'admin':
                return redirect()->route('admin.index')
                    ->with('error', 'Bạn không có quyền truy cập trang này.');
            case 'doctor':
                return redirect()->route('doctor.index')
                    ->with('error', 'Bạn không có quyền truy cập trang này.');
            case 'schedule_manager':
                return redirect()->route('manager.index')
                    ->with('error', 'Bạn không có quyền truy cập trang này.');
            default:
                return redirect()->route('login')
                    ->with('error', 'Tài khoản không hợp lệ.');
        }
    }
}
