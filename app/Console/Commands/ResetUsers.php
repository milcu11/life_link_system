<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetUsers extends Command
{
    protected $signature = 'users:reset';
    protected $description = 'Delete all non-admin users and their related data';

    public function handle()
    {
        if (!$this->confirm('This will delete ALL non-admin users and their related data. Continue?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        try {
            // Get all non-admin users
            $nonAdminUsers = User::where('role', '!=', 'admin')->get();
            $count = $nonAdminUsers->count();

            if ($count === 0) {
                $this->info('No non-admin users to delete.');
                return 0;
            }

            $this->info("Found {$count} non-admin user(s) to delete.");

            foreach ($nonAdminUsers as $user) {
                // Delete related donor records
                if ($user->donor) {
                    $user->donor->delete();
                }

                // Delete related blood requests (cascades to donations, matches, notifications)
                $user->bloodRequests()->delete();

                // Delete user notifications
                $user->notifications()->delete();

                // Delete the user
                $user->delete();

                $this->line("✓ Deleted user: {$user->email}");
            }

            $this->info("\n✅ Successfully deleted {$count} user(s)!");
            
            // Show remaining admin users
            $adminCount = User::where('role', 'admin')->count();
            $this->info("Remaining admin user(s): {$adminCount}");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return 1;
        }
    }
}
