<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['status' => 'For Rent', 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'For Sale', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('statuses')->insert($statuses);

        $this->command->info('Successfully seeded property statuses!');
    }
}
