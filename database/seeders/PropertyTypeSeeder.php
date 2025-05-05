<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['type' => 'Villa', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Apartment', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Cabin', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Condo', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Townhouse', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Studio', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Loft', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Bungalow', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Chalet', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Farmhouse', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Penthouse', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Duplex', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('types')->insert($types);

        $this->command->info('Successfully seeded property types!');
    }
}
