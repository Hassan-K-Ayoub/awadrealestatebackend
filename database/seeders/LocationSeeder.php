<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            ['location' => 'Dawhet al hoss', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bchamoun', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Aramoun', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Khalde', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Naameh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Damour', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Mechref', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jiyeh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Barja', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Rmeileh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Saadiyat', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Debiyeh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Saida', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Choueifat', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Deir Qoubel', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bir Hasan', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jneh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Rouche', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ramlet El Bayda', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Manara', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ein El Mrayseh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Downtown', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Saifi', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Achrafieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Verdun', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Kraitem', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ein El Tineh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hazmieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baabda', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Badaro', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Tayyouneh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Mar Takla', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
