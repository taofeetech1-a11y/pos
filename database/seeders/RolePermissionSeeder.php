<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $permissions = [
            //
            'user.manage',
            'role.manage',
            'permission.manage',

            //
            'product.manage',
            'brand.manage',
            'supplier.manage',
            'productCategory.manage',
            'productSupplied.manage',

            //
            'sale.create',
            'sale.details',
            'sale.refund',

            //
            'sale.report',
            'dashboard.view'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Define roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $storeManger = Role::firstOrCreate(['name' => 'store-manager', 'guard_name' => 'web']);
        $cashier = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $auditor = Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);

        // Attach permissions
        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo([
            'product.manage',
            'brand.manage',
            'supplier.manage',
            'productCategory.manage',
            'productSupplied.manage',
            'sale.create',
            'sale.details',
            'sale.refund',
            'sale.report',
            'dashboard.view',
            'user.manage',
            'role.manage'
        ]);
        $storeManger->givePermissionTo([
            'product.manage',
            'productSupplied.manage',
            'supplier.manage',
            'dashboard.view'

        ]);
        $cashier->givePermissionTo([
            'sale.create',
            'sale.details',
            'sale.refund',
        ]);
        $accountant->givePermissionTo([
            'sale.report',
            'dashboard.view'

        ]);
        $auditor->givePermissionTo([
            'sale.report'
        ]);
    }
}
