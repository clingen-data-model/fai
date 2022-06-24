<?php

namespace Tests\Feature\End2End;

use Tests\Feature\End2End\TestCase;
use App\Events\FunctionalAssaySaved;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Event;
use App\Events\FunctionalAssayCreated;
use Tests\traits\SetsUpFunctionalAssay;
use Tests\traits\FunctionalAssayTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FunctionalAssayCreateTest extends TestCase
{
    use FunctionalAssayTestHelpers;
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->createAssayClass();    
    }
    

    /**
     * @test
     */
    public function creates_FunctionalAssay()
    {
        $expected = $this->getDefaultData();
        unset($expected['assay_class_ids']);

        $this->makeRequest()
            ->assertStatus(201)
            ->assertJson($expected);

        
        $this->assertDatabaseHas('functional_assays', $this->jsonifyArrays($expected));
    }

    /**
     * @test
     */
    public function validates_required_parameters()
    {
        $this->makeRequest([])
            ->assertValidationErrors([
                'affiliation_id' => 'This is required.',
                'publication_id' => 'This is required.',
                'replication' => 'This is required.',
                'statistical_analysis_description' => 'This is required.',
                'range_type' => 'This is required.',
                'assay_class_ids' => 'This is required.',
            ]);
    }

    /**
     * @test
     */
    public function validates_parameter_formats()
    {
        $this->makeRequest([
            'affiliation_id' => 'blah',
            'publication_id' => 999,
            'hgnc_id' => 'blah',
            'range_type' => 'blah',
            'approved' => 'blah',
            'material_used' => 'blah',
            'patient_derived_material_used' => 'blah',
            
            'range' => str_repeat("X", 256),
            'normal_range' => str_repeat("X", 256),
            'abnormal_range' => str_repeat("X", 256),
            'indeterminate_range' => str_repeat("X", 256),
            'validation_control_pathogenic' => str_repeat("X", 256),
            'validation_control_benign' => str_repeat("X", 256),
            'significance_threshold' => str_repeat("X", 256),
            'units' => str_repeat('X', 256),
            'field_notes' => 'beans'
        ])
        ->assertValidationErrors([
            'affiliation_id' => 'This must be an integer.',
            'publication_id' => 'The selection is invalid.',
            'hgnc_id' => 'The format is invalid.',
            'range_type' => 'The selection is invalid.',
            'approved' => 'This must be true or false.',
            'field_notes' => 'This must be an array.',
            'validation_control_pathogenic' => 'This must be an integer.',
            'validation_control_benign' => 'This must be an integer.',
            
        ]);
    }
    
    /**
     * @test
     */
    public function fires_FunctionalAssayCreated_event()
    {
        Event::fake();

        $this->makeRequest()
            ->assertStatus(201);

        Event::assertDispatched(FunctionalAssayCreated::class, function ($event) {
            return $event->functionalAssay->hgnc_id == 'HGNC:12345';
        });
    }

    /**
     * @test
     */
    public function fires_FunctionalAssaySaved_event()
    {
        Event::fake();

        $this->makeRequest()
            ->assertStatus(201);

        Event::assertDispatched(FunctionalAssaySaved::class, function ($event) {
            return $event->functionalAssay->hgnc_id == 'HGNC:12345';
        });
    }
    
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? $this->getDefaultData();

        return $this->json('POST', '/api/functional-assays', $data);
    }
    
}
