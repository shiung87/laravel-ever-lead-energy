<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'module' => 'dashboard'],
            ['name' => 'View Customer Leads', 'slug' => 'leads.view', 'module' => 'leads'],
            ['name' => 'Manage Customer Leads', 'slug' => 'leads.manage', 'module' => 'leads'],
            ['name' => 'Assign Customer Leads', 'slug' => 'leads.assign', 'module' => 'leads'],
            ['name' => 'Export Customer Leads', 'slug' => 'leads.export', 'module' => 'leads'],
            ['name' => 'View Dealer Applications', 'slug' => 'dealers.view', 'module' => 'dealers'],
            ['name' => 'Manage Dealer Applications', 'slug' => 'dealers.manage', 'module' => 'dealers'],
            ['name' => 'Export Dealer Applications', 'slug' => 'dealers.export', 'module' => 'dealers'],
            ['name' => 'Manage Products', 'slug' => 'products.manage', 'module' => 'catalog'],
            ['name' => 'Manage Brands & Battery Types', 'slug' => 'brands.manage', 'module' => 'catalog'],
            ['name' => 'Manage Vehicle Compatibility', 'slug' => 'vehicles.manage', 'module' => 'catalog'],
            ['name' => 'Manage Promotions & Campaigns', 'slug' => 'promotions.manage', 'module' => 'marketing'],
            ['name' => 'Manage Homepage Banners', 'slug' => 'banners.manage', 'module' => 'marketing'],
            ['name' => 'Manage Testimonials', 'slug' => 'testimonials.manage', 'module' => 'marketing'],
            ['name' => 'Manage Contact Enquiries', 'slug' => 'enquiries.manage', 'module' => 'enquiries'],
            ['name' => 'Manage Website & SEO Settings', 'slug' => 'settings.manage', 'module' => 'settings'],
            ['name' => 'Manage Users & Roles', 'slug' => 'users.manage', 'module' => 'users'],
            ['name' => 'View Audit Logs', 'slug' => 'audit.view', 'module' => 'audit'],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            $record = Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
            $permissionIds[$perm['slug']] = $record->id;
        }

        $rolesConfig = [
            'super-admin' => [
                'name' => 'Super Administrator',
                'description' => 'Full unrestricted system access.',
                'is_system' => true,
                'perms' => array_keys($permissionIds),
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'Manage website, catalog, leads, dealers, and business operations.',
                'is_system' => true,
                'perms' => array_values(array_diff(array_keys($permissionIds), ['users.manage'])),
            ],
            'sales-manager' => [
                'name' => 'Sales Manager',
                'description' => 'View, assign, manage, and export customer leads and dealer applications.',
                'is_system' => true,
                'perms' => ['dashboard.view', 'leads.view', 'leads.manage', 'leads.assign', 'leads.export', 'dealers.view', 'dealers.manage', 'dealers.export', 'enquiries.manage'],
            ],
            'sales-executive' => [
                'name' => 'Sales Executive',
                'description' => 'Manage assigned customer leads and follow-ups.',
                'is_system' => true,
                'perms' => ['dashboard.view', 'leads.view', 'leads.manage', 'dealers.view'],
            ],
            'marketing-manager' => [
                'name' => 'Marketing Manager',
                'description' => 'Manage promotions, banners, testimonials, products, and SEO settings.',
                'is_system' => true,
                'perms' => ['dashboard.view', 'promotions.manage', 'banners.manage', 'testimonials.manage', 'products.manage', 'brands.manage', 'settings.manage'],
            ],
        ];

        $roles = [];
        foreach ($rolesConfig as $slug => $data) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                ['name' => $data['name'], 'description' => $data['description'], 'is_system' => $data['is_system']]
            );
            $role->permissions()->sync(array_map(fn ($p) => $permissionIds[$p], $data['perms']));
            $roles[$slug] = $role;
        }

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@everleadenergy.com', 'phone' => '+60123456780', 'role' => 'super-admin'],
            ['name' => 'Operations Admin', 'email' => 'admin@everleadenergy.com', 'phone' => '+60123456781', 'role' => 'admin'],
            ['name' => 'Daniel Tan', 'email' => 'salesmanager@everleadenergy.com', 'phone' => '+60123456782', 'role' => 'sales-manager'],
            ['name' => 'Aiman Hakim', 'email' => 'sales@everleadenergy.com', 'phone' => '+60123456783', 'role' => 'sales-executive'],
            ['name' => 'Rachel Lim', 'email' => 'marketing@everleadenergy.com', 'phone' => '+60123456784', 'role' => 'marketing-manager'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'phone' => $u['phone'],
                    'role_id' => $roles[$u['role']]->id,
                    'password' => Hash::make('Password@123'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
