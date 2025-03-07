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
        DB::table('roles')->insert([
            ['name' => 'Admin', 'guard_name' =>'web'],
            ['name' => 'Manager', 'guard_name' =>'web'],
            ['name' => 'User', 'guard_name' =>'web']
        ]);
    }
}
