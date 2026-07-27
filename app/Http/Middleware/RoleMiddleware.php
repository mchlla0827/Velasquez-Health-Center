<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role);
        $allowedRoles = array_map('strtolower', $roles);

        // ✅ BAGONG TAMBAH: SPECIAL PERMISSION PARA SA PHYSICIAN IN CHARGE
        // Kung ang pahina ay para sa PIC/Admin, at ang user ay Doctor na may is_physician_in_charge = 1,
        // papasukin natin siya kahit hindi nakalista ang role sa allowedRoles
        $isAccessingPICPage = in_array('admin', $allowedRoles) || in_array('pic', array_map('strtolower', $roles));
        if ($isAccessingPICPage && $userRole === 'doctor' && $user->is_physician_in_charge == 1) {
            // ✅ PAPASUKIN: Siya ang PIC, may karapatan siya kahit Doctor lang ang role niya
            return $next($request);
        }

        // 2. Check if user has one of the required roles for this route
        if (!in_array($userRole, $allowedRoles)) {
            
            // PERMANENT FIX: If they are logged in but on the WRONG page,
            // send them to their own dashboard instead of the login page.
            return match ($userRole) {
                'admin'  => redirect('/admin/dashboard'),
                'nurse'  => redirect('/nurse/dashboard'),
                'doctor' => redirect('/doctor/dashboard'),
                'bhw'    => redirect('/bhw/dashboard'),
                default  => redirect('/login'),
            };
        }

        return $next($request);
    }
}