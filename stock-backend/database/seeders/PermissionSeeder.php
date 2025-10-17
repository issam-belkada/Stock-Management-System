<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
    // User & Role Management
    'manage_users', 'view_users', 'manage_roles', 'manage_permissions',

    // Product & Inventory
    'create_product', 'edit_product', 'delete_product', 'view_products',
    'manage_categories', 'manage_suppliers', 'manage_stock',

    // Sales & POS
    'make_sales', 'view_sales', 'cancel_sales', 'manage_pos',

    // Reports
    'view_reports', 'export_reports',

    // Notifications
    'view_notifications', 'manage_notifications',
];


        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }
}
