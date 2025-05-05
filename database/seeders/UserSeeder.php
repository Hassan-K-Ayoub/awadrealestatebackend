<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Ziad Awad', 'email' => 'contact@awadsrealestate.com', 'password' => '$2y$12$88wUUkn9O.hhdQ6jNuId7uUiQV5pO1spxLq2OwGbkOdcdJvVVOvXe'],
        ];

        DB::table('users')->insert($users);

        $this->command->info('Successfully seeded users!');
    }
}
