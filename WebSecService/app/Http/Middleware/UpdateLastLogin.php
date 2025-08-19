<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // تحديث آخر تسجيل دخول إذا كان المستخدم مسجل دخول
        if (Auth::check()) {
            $user = Auth::user();
            
            // تحديث آخر تسجيل دخول كل 5 دقائق فقط لتجنب التحديث المستمر
            if (!$user->last_login_at || $user->last_login_at->diffInMinutes(now()) >= 5) {
                $user->updateLastLogin();
            }
        }

        return $response;
    }
}
