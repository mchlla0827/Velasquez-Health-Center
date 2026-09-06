<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle post-authentication logic — THIS RUNS ON EVERY SUCCESSFUL LOGIN
     */
    protected function authenticated(Request $request, $user)
    {
       
        $user->last_login_at = now();
        $user->save();

      
        session([
            'admin_id'     => $user->id,
            'admin_name'   => $user->name,
            'admin_role'   => $user->role,
            'is_pic'       => $user->is_pic ?? 0,
        ]);

        return redirect()->intended($this->redirectPath());
    }
}