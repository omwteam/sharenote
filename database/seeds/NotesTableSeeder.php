<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $container = [];
        for ($i = 0; $i < 100; $i++) {
            array_push(
                $container,
                [
                    'title' => str_random(12),
                    'content' => 'js-'.str_random(30),
                    'u_id' => rand(1,2),
                    'f_id' => rand(1,20)
                ]
            );
        }
        DB::table('notes')->insert($container);
    }
}
