<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CodingSystemsSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $seeders = [
            CodingSystemsSeeder::class,
        ];

        foreach ($seeders as $seederClass) {
            $this->call($seederClass);
        }
    }
}
