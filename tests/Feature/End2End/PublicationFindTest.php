<?php

namespace Tests\Feature\End2End;

use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\SetsUpPublication;

class PublicationFindTest extends TestCase
{
    use SetsUpPublication;
    
    /**
     * @test
     */
    public function reponds_with_assay_class()
    {
        $this->publication->load('codingSystem');

        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson($this->publication->toArray());
    }

    private function makeRequest($id = null): TestResponse
    {
        $id = $id ?? $this->publication->id;
        return $this->json('GET', '/api/publications/'.$id);
    }
    
    
    
}
