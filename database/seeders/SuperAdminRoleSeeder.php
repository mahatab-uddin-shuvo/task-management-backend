<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use DB;

class SuperAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role               =  new Role();
        $role->name         = "SuperAdmin";
        $role->guard_name   = "api";

        $role->save();

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => "App\Models\User",
            'model_id' => 1
        ]);
    }
}
