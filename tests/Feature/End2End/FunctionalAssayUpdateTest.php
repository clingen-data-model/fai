<?php

namespace Tests\Feature\End2End;

use App\Models\Publication;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\traits\SetsUpPublication;

class FunctionalAssayUpdateTest extends TestCase
{
    use RefreshDatabase;
    use SetsUpPublication;

    /**
     * @test
     */
    public function updates_an_assay_class()
    {
        $this->markTestSkipped();
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson([
                'title' => 'some title',
            ]);

        $this->assertDatabaseHas('publications',  [
            'id' => $this->publication->id,
            'title' => 'some title',
        ]);
    }

    /**
     * @test
     */
    public function validates_parameters()
    {
        $this->markTestSkipped();
        $this->makeRequest([])
            ->assertValidationErrors([
                'title' => 'This is required.',
            ]);

        $this->makeRequest(['title' => str_repeat('x', 256)])
            ->assertValidationErrors([
                'title' => 'This must not be greater than 255 characters.'
            ]);
    }
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? [
            'code' => $this->publication->code,
            'coding_system_id' => $this->publication->coding_system_id,
            'title' => 'some title',
        ];

        return $this->json('PUT', '/api/publications/'.$this->publication->id, $data);
    }
}
