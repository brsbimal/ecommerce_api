<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles  = [
            [
                'user_id' => '1',
                'role' => 'admin'
            ],
            [
                'user_id' => '2',
                'role' => 'moderator'
            ],
            [
                'user_id' => '3',
                'role' => 'customer'
            ],
            [
                'user_id' => '4',
                'role' => 'guest'
            ]
        ];
        foreach($roles as $role){
            DB::table('roles')->insert([
                'user_id' => $role['user_id'],
                'role' => $role['role']
            ]);
        }
    }
}
