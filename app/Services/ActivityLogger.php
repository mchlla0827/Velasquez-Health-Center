<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * ActivityLogger
 * ===============
 * Centralized helper for the EXISTING Activity Log - not a new system.
 * Every call auto-fills user name/role from the authenticated user, so
 * every caller only needs to supply what actually happened.
 *
 * Only call this for actions that genuinely CHANGE data (create,
 * update, delete, dispense, login/logout, etc.) - never for page
 * views, modal opens, or other read-only navigation.
 */
class ActivityLogger
{
    public static function log(string $action, string $module, string $details, ?string $type = null): void
    {
        $user = Auth::user();

        ActivityLog::create([
            'user_name' => $user->name ?? session('admin_name') ?? 'System',
            'role' => $user->role ?? session('admin_role') ?? null,
            'action' => $action,
            'module' => $module,
            'details' => $details,
            'type' => $type ?? strtoupper(explode(' ', $action)[0]),
        ]);
    }
}