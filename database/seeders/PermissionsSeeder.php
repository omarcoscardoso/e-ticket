<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->truncate();
        DB::table('permissions')->insert([
            ['id' => '1', 'name' => 'access_painel', 'guard_name' =>'web'],
            ['id' => '2', 'name' => 'user_read', 'guard_name' =>'web'],
            ['id' => '3', 'name' => 'user_create', 'guard_name' =>'web'],
            ['id' => '4', 'name' => 'user_update', 'guard_name' =>'web'],
            ['id' => '5', 'name' => 'user_delete', 'guard_name' =>'web'],
            ['id' => '6', 'name' => 'role_read', 'guard_name' =>'web'],
            ['id' => '7', 'name' => 'role_create', 'guard_name' =>'web'],
            ['id' => '8', 'name' => 'role_update', 'guard_name' =>'web'],
            ['id' => '9', 'name' => 'role_delete', 'guard_name' =>'web'],
            ['id' => '10', 'name' => 'permission_read', 'guard_name' =>'web'],
            ['id' => '11', 'name' => 'permission_create', 'guard_name' =>'web'],
            ['id' => '12', 'name' => 'permission_update', 'guard_name' =>'web'],
            ['id' => '13', 'name' => 'permission_delete', 'guard_name' =>'web'],
            ['id' => '14', 'name' => 'evento_read', 'guard_name' =>'web'],
            ['id' => '15', 'name' => 'evento_create', 'guard_name' =>'web'],
            ['id' => '16', 'name' => 'evento_update', 'guard_name' =>'web'],
            ['id' => '17', 'name' => 'evento_delete', 'guard_name' =>'web'],
            ['id' => '18', 'name' => 'igreja_read', 'guard_name' =>'web'],
            ['id' => '19', 'name' => 'igreja_create', 'guard_name' =>'web'],
            ['id' => '20', 'name' => 'igreja_update', 'guard_name' =>'web'],
            ['id' => '21', 'name' => 'igreja_delete', 'guard_name' =>'web'],
            ['id' => '22', 'name' => 'ingresso_read', 'guard_name' =>'web'],
            ['id' => '23', 'name' => 'ingresso_create', 'guard_name' =>'web'],
            ['id' => '24', 'name' => 'ingresso_update', 'guard_name' =>'web'],
            ['id' => '25', 'name' => 'ingresso_delete', 'guard_name' =>'web'],
            ['id' => '26', 'name' => 'inscrito_read', 'guard_name' =>'web'],
            ['id' => '27', 'name' => 'inscrito_create', 'guard_name' =>'web'],
            ['id' => '28', 'name' => 'inscrito_update', 'guard_name' =>'web'],
            ['id' => '29', 'name' => 'inscrito_delete', 'guard_name' =>'web']
        ]);
    }
}
