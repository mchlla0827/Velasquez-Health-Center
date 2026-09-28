<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
            'contact_number' => ['required', 'string', 'max:20'],
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

        \App\Services\ActivityLogger::log(
            'User created',
            'Users',
            (\App\Models\User::find(session('user_id'))->name ?? 'Admin') . ' created a new user account: ' . $user->name . ' (' . $user->role . ').',
            'CREATE'
        );

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

        \App\Services\ActivityLogger::log(
            'User information updated',
            'Users',
            (\App\Models\User::find(session('user_id'))->name ?? 'Admin') . ' updated the account of ' . $user->name . '.',
            'UPDATE'
        );

        return redirect()->route('admin.manage-user')->with('success', 'User account updated successfully!');
    }

    /**
     * Delete a user account. Prevents an admin from deleting their own
     * currently logged-in account (would lock them out).
     */
    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account while logged in.');
        }

        $userName = $user->name;

        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()->with('error', $userName . ' cannot be deleted because they have historical records (consultations, NCD assessments, dispensing, etc.) linked to their account. This protects patient history from being lost. Consider deactivating the account instead of deleting it.');
            }
            throw $e;
        }

        \App\Services\ActivityLogger::log(
            'User archived/deactivated',
            'Users',
            (auth()->user()->name ?? 'Admin') . ' deleted the account of ' . $userName . '.',
            'DELETE'
        );

        return redirect()->route('admin.manage-user')->with('success', 'User account deleted successfully.');
    }
}