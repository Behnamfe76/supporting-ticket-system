<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions with descriptions
        $permissions = [
            // Tickets
            ['name' => 'ticket.create', 'description' => 'Create new support tickets'],
            ['name' => 'ticket.view', 'description' => 'View support tickets'],
            ['name' => 'ticket.update', 'description' => 'Update support tickets'],
            ['name' => 'ticket.delete', 'description' => 'Delete support tickets'],
            ['name' => 'ticket.assign', 'description' => 'Assign tickets to staff'],
            ['name' => 'ticket.close', 'description' => 'Close resolved tickets'],
            // Replies
            ['name' => 'reply.create', 'description' => 'Create replies to tickets'],
            ['name' => 'reply.view', 'description' => 'View replies to tickets'],
            ['name' => 'reply.update', 'description' => 'Update replies to tickets'],
            ['name' => 'reply.delete', 'description' => 'Delete replies to tickets'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                ['description' => $perm['description']]
            );
        }

        // Define roles with descriptions
        $roles = [
            'super-admin' => 'Full access to all system features',
            'admin'       => 'Manage system, users, and tickets',
            'staff'       => 'Handle assigned tickets and view dashboard',
            'user'        => 'Create and view their own tickets',
        ];

        foreach ($roles as $name => $description) {
            Role::updateOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // Assign permissions
        $allPermissions = Permission::all();

        Role::findByName('super-admin')->syncPermissions($allPermissions);
        Role::findByName('admin')->syncPermissions($allPermissions);

        Role::findByName('staff')->syncPermissions([
            'ticket.view',
            'ticket.update',
            'ticket.assign',
            'ticket.close',
            'reply.create',
            'reply.view',
            'reply.update',
            'reply.delete',
        ]);

        Role::findByName('user')->syncPermissions([
            'ticket.create',
            'ticket.view',
            'reply.create',
            'reply.view',
        ]);
    }
}
