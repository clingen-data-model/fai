<?php

namespace Tests\Feature\End2End;

use Tests\Feature\End2End\TestCase;
use Tests\traits\SetsUpPublication;
use Illuminate\Testing\TestResponse;

class PublicationCreateTest extends TestCase
{
    use SetsUpPublication;

    /**
     * @test
     */
    public function creates_publication()
    {
        $this->makeRequest()
            ->assertStatus(201)
            ->assertJson([
                'coding_system_id' => $this->codingSystem->id,
                'code' => '12345',
            ]);

        $this->assertDatabaseHas('publications', [
            'coding_system_id' => $this->codingSystem->id,
            'code' => '12345',
        ]);
    }

    /**
     * @test
     */
    public function validates_request_parameters()
    {
        $this->makeRequest([])
            ->assertValidationErrors([
                'coding_system_id' => 'This is required.',
                'code' => 'This is required.',
            ]);

        $this->makeRequest([
            'coding_system_id' => $this->publication->coding_system_id,
            'code' => $this->publication->code
        ])
        ->assertValidationErrors([
            'code' => 'The code has already been taken.'
        ]);
    }
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? [
            'coding_system_id' => $this->codingSystem->id,
            'code' => '12345',
        ];

        return $this->json('POST', '/api/publications', $data);
    }
    
    
}
