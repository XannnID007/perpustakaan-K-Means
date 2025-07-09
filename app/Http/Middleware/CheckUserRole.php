<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Jika admin mengakses halaman user, redirect ke admin
            if ($user->isAdmin() && !$request->is('admin*') && !$request->is('api*') && !$request->is('logout')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
