<?php

namespace Database\Seeders;

use App\Models\CodingSystem;
use Database\Seeders\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CodingSystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systems = [
            [
                'id' => 1,
                'name' => 'PubMed'
            ],
            [
                'id' => 2,
                'name' => 'URI'
            ]
        ];

        $this->seedFromArray($systems, CodingSystem::class);
    }
}
