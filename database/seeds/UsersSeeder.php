<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'manage user',
            'create user'
        ];


        foreach ($permissions as $ap) {
            Permission::create(['name' => $ap]);
        }

        $permissionAdmin = [
            'manage user'

        ];



        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        foreach ($permissionAdmin as $ap) {
            $permission = Permission::findByName($ap);
            $adminRole->givePermissionTo($permission);
        }

        $userAdmin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('rahasia')
        ]);

        $userAdmin->assignRole($adminRole);
    }
}
