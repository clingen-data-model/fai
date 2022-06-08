<?php

namespace Tests\Feature\End2End;

use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\SetsUpCodingSystem;

class CodingSystemCreateTest extends TestCase
{
    use SetsUpCodingSystem;

    /**
     * @test
     */
    public function creates_a_new_coding_system_from_data()
    {
        $this->makeRequest()
            ->assertStatus(201)
            ->assertJson([
                'name' => 'test name',
            ]);
        
        $this->assertDatabaseHas('coding_systems', [
            'name' => 'test name',
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

        $this->makeRequest(['name' => $this->codingSystem->name])
            ->assertValidationErrors([
                'name' => 'The name has already been taken.'
            ]);
    }

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? [
            'name' => 'test name',
        ];
        $response = $this->json('POST', '/api/coding-systems', $data);

        return $response;
    }
}
