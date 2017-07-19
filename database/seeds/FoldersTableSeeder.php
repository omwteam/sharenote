<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('folders')->insert([
           [
               'title' => 'Andriod技术',
               'u_id' => 1,
               'p_id' => 0,
               'active' => '1',
           ],
            [
                'title' => 'IOS技术',
                'u_id' => 1,
                'p_id' => 0,
                'active' => '1',
            ],
            [
                'title' => 'C/C++语言',
                'u_id' => 1,
                'p_id' => 0,
                'active' => '1',
            ],
            [
                'title' => 'Web前端',
                'u_id' => 1,
                'p_id' => 0,
                'active' => '1',
            ],
            [
                'title' => '服务器技术开发',
                'u_id' => 1,
                'p_id' => 0,
                'active' => '1',
            ],
            [
                'title' => '效率工具',
                'u_id' => 1,
                'p_id' => 0,
                'active' => '1',
            ],
            [
                'title' => '产品设计',
                'u_id' => 1,
                'p_id' => 0,
                'active' => '1',
            ],
        ]);
    }
}
