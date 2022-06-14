<?php

namespace Tests\Feature\End2End;

use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\FunctionalAssayTestHelpers;

class FunctionalAssayFindTest extends TestCase
{
    use FunctionalAssayTestHelpers;
    
    /**
     * @test
     */
    public function reponds_with_assay_class()
    {
        $this->functionalAssay->load('assayClasses');

        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson($this->functionalAssay->toArray());
    }

    private function makeRequest($id = null): TestResponse
    {
        $id = $id ?? $this->functionalAssay->id;
        return $this->json('GET', '/api/functional-assays/'.$id);
    }
    
    
    
}
