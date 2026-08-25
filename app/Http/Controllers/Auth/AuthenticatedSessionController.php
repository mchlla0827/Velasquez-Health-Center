<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AuthenticatedSessionController extends Controller
{
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // ✅ Record when the user actually logged in so "Active/Offline" status works
        $user->last_login_at = now();
        $user->save();

        $this->setSessionData($user);

        return $this->redirectBasedOnRole($user);
    }

    protected function setSessionData($user)
    {
        $role = strtolower($user->role);

        session([
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
        ]);

        if ($role === 'admin') {
            session([
                'is_admin_logged_in' => true,
                'admin_name'         => $user->name,
                'admin_role'         => 'Admin'
            ]);
        }
    }

    protected function redirectBasedOnRole($user)
    {
        $role = strtolower($user->role);

        return match ($role) {
            'admin'  => redirect()->intended('/admin/dashboard'),
            'nurse'  => redirect()->intended('/nurse/dashboard'),
            'doctor' => redirect()->intended('/doctor/dashboard'),
            'bhw'    => redirect()->intended('/bhw/dashboard'),
            default  => redirect('/login'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (session('is_admin_logged_in')) {
            ActivityLog::create([
                'user_name' => session('admin_name'),
                'action'    => 'Logged out',
                'details'   => 'User logged out of the system',
                'type'      => 'LOGOUT',
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}