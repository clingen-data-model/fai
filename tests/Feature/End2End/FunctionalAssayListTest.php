<?php

namespace Tests\Feature\End2End;

use App\Models\FunctionalAssay;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\FunctionalAssayTestHelpers;

class FunctionalAssayListTest extends TestCase
{
    use FunctionalAssayTestHelpers;

    public function setup():void
    {
        parent::setup();
        $this->createFunctionalAssay();
        $this->functionalAssays = FunctionalAssay::all();
    }

    /**
     * @test
     */
    public function responds_with_all_assay_classes()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson($this->functionalAssays->toArray());
    }

    private function makeRequest($query = null): TestResponse
    {
        $query = $query ?? [];
        return $this->json('GET', '/api/functional-assays', $query);
    }
    
    
    
}
