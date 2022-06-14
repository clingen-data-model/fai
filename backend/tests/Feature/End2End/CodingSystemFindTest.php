<?php

namespace Tests\Feature\End2End;

use App\Models\CodingSystem;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\End2End\CodingSystemTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\traits\SetsUpCodingSystem;

class CodingSystemFindTest extends TestCase
{
    use SetsUpCodingSystem;
    
    /**
     * @test
     */
    public function reponds_with_assay_class()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson($this->codingSystem->toArray());
    }

    private function makeRequest($id = null): TestResponse
    {
        $id = $id ?? $this->codingSystem->id;
        return $this->json('GET', '/api/coding-systems/'.$id);
    }
    
    
    
}
