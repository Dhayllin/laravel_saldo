<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Dhayllin Jesuss',
            'email' => 'dhayllin@hotmail.com',
            'password' => bcrypt('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Outro Usuario',
            'email' => 'outro@hotmail.com',
            'password' => bcrypt('123456'),
        ]);

        DB::table('users')->insert([
            'name' => 'Robert',
            'email' => 'robert@hotmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
