<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            ['name' => 'Admin', 'guard_name' =>'web'],
            ['name' => 'Manager', 'guard_name' =>'web'],
            ['name' => 'User', 'guard_name' =>'web']
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
