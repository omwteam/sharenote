<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'softteam',
            'email' => 'softteam@gmail.com',
            'password' => bcrypt('123456'),
            'auth' => 2,
        ]);
    }
}
