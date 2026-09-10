<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default administrator account.
     *
     * IMPORTANT: change this password immediately after first login
     * on any real deployment. This seeder exists only so that
     * `php artisan db:seed` (and therefore `migrate:fresh --seed`)
     * completes successfully on a fresh install and leaves the system
     * with at least one usable Admin account.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@velasquezhealthcenter.local'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'admin',
                'is_physician_in_charge' => 0,
            ]
        );
    }
}