<?php

namespace Tests\Feature\End2End;

use App\Models\AssayClass;
use Tests\Feature\End2End\TestCase;
use Illuminate\Testing\TestResponse;
use Tests\traits\SetsUpAssayClass;

class AssayClassListTest extends TestCase
{
    use SetsUpAssayClass;

    public function setup():void
    {
        parent::setup();
        $this->createAssayClass();
        $this->assayClasses = AssayClass::all();
    }

    /**
     * @test
     */
    public function responds_with_all_assay_classes()
    {
        $this->makeRequest()
            ->assertStatus(200)
            ->assertJsonCount($this->assayClasses->count());
    }

    /**
     * @test
     */
    public function can_filter_by_name()
    {
        $firstClass = $this->assayClasses->first();

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
        return $this->json('GET', '/api/assay-classes', $query);
    }



}
