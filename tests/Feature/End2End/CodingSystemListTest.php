<?php

namespace Tests\Feature\End2End;

use App\Models\CodingSystem;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\SetsUpCodingSystem;

class CodingSystemListTest extends TestCase
{
    use SetsUpCodingSystem;

    public function setup():void
    {
        parent::setup();
        $this->createCodingSystem();
        $this->codingSystems = CodingSystem::all();
    }

    /**
     * @test
     */
    public function responds_with_all_assay_classes()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson($this->codingSystems->toArray());
    }

    /**
     * @test
     */
    public function can_filter_by_name()
    {
        $firstClass = $this->codingSystems->first();

        $this->makeRequest(['filter' => substr($firstClass->name, 1, -1)])
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                $firstClass->toArray()
            ]);
    }
    

    private function makeRequest($query = null): TestResponse
    {
        $query = $query ?? [];
        return $this->json('GET', '/api/coding-systems', $query);
    }
    
    
    
}
