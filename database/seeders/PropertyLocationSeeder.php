<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['location' => 'Beirut', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Tripoli', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Sidon', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Tyre', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Byblos', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zahle', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jounieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baabda', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Batroun', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Aley', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Nabatieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jbeil', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bcharre', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zgharta', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hermel', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Marjayoun', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Rashaya', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jezzine', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bint Jbeil', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Chouf', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Metn', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Keserwan', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Akkar', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baalbek', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hasbaya', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Minieh-Danniyeh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bhamdoun', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Broummana', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Dbayeh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Fanar', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ghazir', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hamra', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hazmieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Kfarhabou', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Rabieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Sin El Fil', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zalka', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Adma', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Antelias', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Dekwaneh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jal El Dib', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zouk Mosbeh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bikfaya', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bsalim', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Mansourieh', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Matn', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Mtein', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ain Saade', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baskinta', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Dhour El Choueir', 'count' => 0, 'created_at' => now(), 'updated_at' => now()],
        ];


        DB::table('locations')->insert($locations);

        $this->command->info('Successfully seeded Lebanese locations!');
    }
}
