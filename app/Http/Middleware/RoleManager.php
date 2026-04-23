<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Hubi in qofku uu Login yahay
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Hubi role-ka qofka galaya
        $userRole = Auth::user()->role;

        if ($userRole !== $role) {
            // Haddii uu qofku isku dayo inuu galo meel aan loo ogalayn, u dir Dashboard-kiisa saxda ah
            return match($userRole) {
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('student.dashboard'),
            };
        }

        return $next($request);
    }
}