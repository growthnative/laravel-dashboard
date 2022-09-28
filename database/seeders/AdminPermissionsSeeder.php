<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Super Admin']);
    
        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user = User::create([
            'id' => 1,
            'first_name' => 'Super Admin', 
            'email' => 'admin@admin.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => bcrypt('password'),
            'phone_number'        => 123456789,
            'address'          => 'USA',
            'state'            => 'UK',
            'city'             => 'XYZ',
            'zip_code'         => 12345,
            'role_id'          => 1,
            'status' => 'Active'
        ]);
    
        $user->assignRole([$role->id]);
    }
}
