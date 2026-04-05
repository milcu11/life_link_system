<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ClearUsers extends Command
{
    protected $signature = 'users:clear';
    protected $description = 'Clear all users except admin from the database';

    public function handle()
    {
        // Find the admin user
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->error('No admin user found in database!');
            return 1;
        }

        $adminId = $admin->id;
        $this->info("Keeping admin user (ID: {$adminId})");

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Delete all users except admin
        User::where('id', '!=', $adminId)->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('All non-admin users have been deleted. Only admin remains.');
        return 0;
    }
}
