<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('admin.create-user');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Keep the validation rules matching your form inputs
        $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'role'           => ['required', 'string'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'is_pic'         => ['nullable'], 
        ]);

        // 2. Map form data into your EXACT database columns from image_2c6f77.png
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'contact_number' => $request->contact_number,

            // ✅ Correct
            'is_physician_in_charge' => $request->boolean('is_physician_in_charge'),
        ]);

        event(new Registered($user));

        return redirect()->back()->with('success', 'User account created successfully!');
    }

    /**
     * Update user details.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'role'           => ['required', 'string'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'is_pic'         => ['nullable'],
        ]);

        $user = User::findOrFail($id);

        // 🔴 FIXED: Maps updates to your real columns
        $user->update([
            'name'                  => $request->first_name . ' ' . $request->last_name,
            'role'                  => $request->role,
            'contact_number'        => $request->contact_number,
            'is_physician_in_charge' => $request->has('is_pic') || $request->is_pic == 1 ? 1 : 0,
        ]);

        return redirect()->route('admin.manage-user')->with('success', 'User account updated successfully!');
    }
}