<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('permissions')->delete();

        \DB::table('permissions')->insert(array(
            0 => array(

                'name' => 'IMS Summary Report',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => array(

                'name' => 'MERCUSYS_AF0C',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => array(

                'name' => 'Admin Edit',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => array(

                'name' => 'IMS Summary Report 2',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => array(

                'name' => 'item',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            5 => array(

                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            6 => array(

                'name' => 'add picture',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            7 => array(

                'name' => 'Delete picture',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            8 => array(

                'name' => 'spy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));

    }
}
