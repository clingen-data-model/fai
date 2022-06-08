<?php

namespace Tests\Feature\End2End;

use App\Models\AssayClass;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\End2End\AssayClassTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\traits\SetsUpAssayClass;

class AssayClassFindTest extends TestCase
{
    use SetsUpAssayClass;
    
    /**
     * @test
     */
    public function reponds_with_assay_class()
    {
        $this->makeRequest()
            ->assertJson($this->assayClass->toArray());
    }

    private function makeRequest($id = null): TestResponse
    {
        $id = $id ?? $this->assayClass->id;
        return $this->json('GET', '/api/assay-classes/'.$id);
    }
    
    
    
}
