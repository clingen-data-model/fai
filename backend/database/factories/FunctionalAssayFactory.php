<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FunctionalAssay>
 */
class FunctionalAssayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'affiliation_id' => $this->faker->numberBetween(1),
            'hgnc_id' => 'HGNC:'.$this->faker->numberBetween(1),
            'replication' => $this->faker->sentences(6, true),
            'statistical_analysis_description' => $this->faker->sentences(6, true),
            'range_type' => $this->faker->randomElement(['quantitative', 'qualitative'])
        ];
    }
}
