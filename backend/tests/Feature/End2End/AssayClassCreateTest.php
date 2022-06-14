<?php

namespace Tests\Feature\End2End;

use App\Models\AssayClass;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\SetsUpAssayClass;

class AssayClassCreateTest extends TestCase
{
    use SetsUpAssayClass;

    /**
     * @test
     */
    public function creates_a_new_assay_class_from_data()
    {
        $this->makeRequest()
            ->assertStatus(201)
            ->assertJson([
                'name' => 'test name',
                'description' => 'test description'
            ]);
        
        $this->assertDatabaseHas('assay_classes', [
            'name' => 'test name',
            'description' => 'test description'
        ]);
    }
    
    /**
     * @test
     */
    public function validates_parameters()
    {
        $this->makeRequest([])
            ->assertValidationErrors([
                'name' => 'This is required.'
            ]);

        $this->makeRequest(['name' => str_repeat('x', 256)])
            ->assertValidationErrors([
                'name' => 'This must not be greater than 255 characters.'
            ]);

        $this->makeRequest(['name' => $this->assayClass->name])
            ->assertValidationErrors([
                'name' => 'The name has already been taken.'
            ]);
    }

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? [
            'name' => 'test name',
            'description' => 'test description'
        ];
        $response = $this->json('POST', '/api/assay-classes', $data);

        return $response;
    }
    
    
    
}
