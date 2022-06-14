<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RawImport>
 */
class RawImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'assay_class' => $this->faker->words(3),
            'affiliation_id' => $this->faker->numberBetween(1),
            'pubmed_id' => $this->faker->numberBetween(1),
            'data' => $this->faker->array()
        ];
    }
}
