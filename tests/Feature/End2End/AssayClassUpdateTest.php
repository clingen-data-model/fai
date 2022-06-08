<?php

namespace Tests\Feature\End2End;

use App\Models\AssayClass;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\traits\SetsUpAssayClass;

class AssayClassUpdateTest extends TestCase
{
    use RefreshDatabase;
    use SetsUpAssayClass;

    /**
     * @test
     */
    public function upates_an_assay_class()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson([
                'name' => 'updated name',
                'description' => 'updated description'
            ]);
    }

    /**
     * @test
     */
    public function validates_parameters()
    {
        $this->makeRequest([])
            ->assertStatus(422)
            ->assertInvalid([
                'name' => 'This is required.',
                'description' => 'This must be present.'
            ]);

        $this->makeRequest(['name' => null, 'description' => null])
            ->assertStatus(422)
            ->assertInvalid([
                'name' => 'This is required.',
            ])
            ->assertValid(['description']);

        $this->makeRequest(['name' => str_repeat('x', 256)])
            ->assertStatus(422)
            ->assertInvalid([
                'name' => 'This must not be greater than 255 characters.'
            ]);

        $otherClass = AssayClass::factory()->create();
        $this->makeRequest(['name' => $otherClass->name, 'description' => null])
            ->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name has already been taken.'
            ]);
    }
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? [
            'name' => 'updated name',
            'description' => 'updated description'
        ];

        return $this->json('PUT', '/api/assay-classes/'.$this->assayClass->id, $data);
    }
    
    
    
}
