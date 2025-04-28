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
            ['location' => 'Beirut', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Tripoli', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Sidon', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Tyre', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Byblos', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zahle', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jounieh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baabda', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Batroun', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Aley', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Nabatieh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jbeil', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bcharre', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zgharta', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hermel', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Marjayoun', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Rashaya', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jezzine', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bint Jbeil', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Chouf', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Metn', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Keserwan', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Akkar', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baalbek', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hasbaya', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Minieh-Danniyeh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bhamdoun', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Broummana', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Dbayeh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Fanar', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ghazir', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hamra', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Hazmieh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Kfarhabou', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Rabieh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Sin El Fil', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zalka', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Adma', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Antelias', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Dekwaneh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Jal El Dib', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Zouk Mosbeh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bikfaya', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Bsalim', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Mansourieh', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Matn', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Mtein', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Ain Saade', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Baskinta', 'created_at' => now(), 'updated_at' => now()],
            ['location' => 'Dhour El Choueir', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('locations')->insert($locations);

        $this->command->info('Successfully seeded Lebanese locations!');
    }
}
