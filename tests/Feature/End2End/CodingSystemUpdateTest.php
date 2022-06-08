<?php

namespace Tests\Feature\End2End;

use App\Models\CodingSystem;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\traits\SetsUpCodingSystem;

class CodingSystemUpdateTest extends TestCase
{
    use RefreshDatabase;
    use SetsUpCodingSystem;

    /**
     * @test
     */
    public function updates_an_assay_class()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson([
                'name' => 'updated name',
            ]);

        $this->assertDatabaseHas('coding_systems',  [
            'name' => 'updated name',
        ]);
    }

    /**
     * @test
     */
    public function validates_parameters()
    {
        $this->makeRequest([])
            ->assertValidationErrors([
                'name' => 'This is required.',
            ]);

        $this->makeRequest(['name' => str_repeat('x', 256)])
            ->assertValidationErrors([
                'name' => 'This must not be greater than 255 characters.'
            ]);

        $otherClass = CodingSystem::factory()->create();
        $this->makeRequest(['name' => $otherClass->name])
            ->assertValidationErrors([
                'name' => 'The name has already been taken.'
            ]);
    }
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? [
            'name' => 'updated name',
        ];

        return $this->json('PUT', '/api/coding-systems/'.$this->codingSystem->id, $data);
    }
    
    
    
}
