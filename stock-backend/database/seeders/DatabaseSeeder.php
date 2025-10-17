<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        RoleSeeder::class,
        PermissionSeeder::class,
        CategorySeeder::class,
        ProductSeeder::class,
        UserSeeder::class,
    ]);

    // 🔐 Assign permissions to roles after seeding
    $adminRole = \App\Models\Role::where('name', 'Admin')->first();
    $managerRole = \App\Models\Role::where('name', 'Manager')->first();
    $cashierRole = \App\Models\Role::where('name', 'Cashier')->first();

    $allPermissions = \App\Models\Permission::pluck('id')->toArray();

    $adminRole->permissions()->sync($allPermissions);

    $managerPermissions = \App\Models\Permission::whereNotIn('name', [
        'manage_users', 'view_users', 'manage_roles', 'manage_permissions'
    ])->pluck('id')->toArray();
    $managerRole->permissions()->sync($managerPermissions);

    $cashierPermissions = \App\Models\Permission::whereIn('name', [
        'view_products', 'make_sales', 'view_sales', 'view_notifications','view_categories','export_reports'
    ])->pluck('id')->toArray();
    $cashierRole->permissions()->sync($cashierPermissions);
}

}
