<?php

namespace Tests\Feature\End2End;

use App\Models\Publication;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\SetsUpPublication;

class PublicationListTest extends TestCase
{
    use SetsUpPublication;

    public function setup():void
    {
        parent::setup();
        $this->createPublication();
        $this->publications = Publication::all();
    }

    /**
     * @test
     */
    public function responds_with_all_assay_classes()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson($this->publications->toArray());
    }

    private function makeRequest($query = null): TestResponse
    {
        $query = $query ?? [];
        return $this->json('GET', '/api/publications', $query);
    }
    
    
    
}
