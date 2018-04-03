<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('node')->insert([
            [
                'name' => 'Root',
                'level' => 0,
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'Child1',
                'level' => 1,
                'lft' => 2,
                'rgt' => 3,
            ],
            [
                'name' => 'Child2',
                'level' => 1,
                'lft' => 4,
                'rgt' => 9,
            ],
            [
                'name' => 'Child2-1',
                'level' => 2,
                'lft' => 5,
                'rgt' => 6,
            ],
            [
                'name' => 'Child2-2',
                'level' => 2,
                'lft' => 7,
                'rgt' => 8,
            ],
        ]);
    }
}
