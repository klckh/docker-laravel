<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Insert a test user
        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
