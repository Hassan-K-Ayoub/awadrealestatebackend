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
            ['status' => 'For Rent', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'For Sale', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'Rented', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'Sold', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('statuses')->insert($statuses);

        $this->command->info('Successfully seeded property statuses!');
    }
}
