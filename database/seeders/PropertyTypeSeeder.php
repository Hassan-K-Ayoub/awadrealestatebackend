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
            ['type' => 'Villa', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Apartment', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Cabin', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Condo', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Townhouse', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Studio', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Loft', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Bungalow', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Chalet', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Farmhouse', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Penthouse', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Duplex', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('types')->insert($types);

        $this->command->info('Successfully seeded property types!');
    }
}
