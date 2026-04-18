<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'user:make-admin {email} {--role=admin : Admin role (root, super_admin, admin, moderator)} {--revoke : Revoke admin access instead of granting it}';

    protected $description = 'Grant or revoke admin access for a user by email';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found with email: {$email}");
            return self::FAILURE;
        }

        if ($this->option('revoke')) {
            $user->admin_role = null;
            $user->save();
            $this->info("Admin access revoked for {$user->name} ({$user->email}).");
            return self::SUCCESS;
        }

        $role = $this->option('role');
        if (! array_key_exists($role, User::ADMIN_ROLES)) {
            $this->error("Invalid role: {$role}. Allowed: " . implode(', ', array_keys(User::ADMIN_ROLES)));
            return self::FAILURE;
        }

        $user->admin_role = $role;
        $user->save();
        $this->info("{$user->name} ({$user->email}) is now {$user->adminRoleLabel()}.");
        return self::SUCCESS;
    }
}
