<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create super admin user
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            ['name' => 'Super Admin',
                'password' => bcrypt('123456'),
                'email_verified_at' => now()
            ]);
        $user->assignRole($role);

        //define all permissions
        $permissions = [
            "user.view",
            "user.create",
            "user.read",
            "user.update",
            "user.delete",
            "role.view",
            "role.create",
            "role.read",
            "role.update",
            "role.delete",
            "permission.view",
            "permission.read",
            "permission.assign",
            "merchant.view",
            "merchant.create",
            "merchant.update",
            "merchant.secret_key_reset",
            "merchant.user.create",
            "merchant.user.update",
            "merchant.user.delete",
            "transaction.view"
        ];
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }
        $administrator_role = Role::create([
            'name'=>'Управляющий'
        ]);
        $manager_role = Role::create([
            'name'=>'Менеджер'
        ]);
        $admin_user = User::firstOrCreate(
            ['email'=>'administrator@email.com'],
            ['name' => 'test',
                'password' => bcrypt('123456'),
                'email_verified_at' => now()
            ]);
        $user->assignRole($role);
        $admin_user->assignRole($administrator_role);
    }
}
