<?php

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    public function run()
    {
        for($i = 1; $i <= 100; $i++) {
            DB::table('tasks')->insert([
                'status' => 'test title ' . $i,
                'content' => 'test content ' . $i
            ]);
        }
    }
}
