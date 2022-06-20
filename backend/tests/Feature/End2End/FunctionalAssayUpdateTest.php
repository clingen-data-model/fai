<?php

namespace Tests\Feature\End2End;

use App\Models\FunctionalAssay;
use Tests\Feature\End2End\TestCase;
use App\Events\FunctionalAssaySaved;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Event;
use App\Events\FunctionalAssayUpdated;
use Tests\traits\FunctionalAssayTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FunctionalAssayUpdateTest extends TestCase
{
    use RefreshDatabase;
    use FunctionalAssayTestHelpers;

    /**
     * @test
     */
    public function updates_an_assay_class()
    {
        $expectedData = $this->getDefaultData();
        unset($expectedData['assay_class_ids']);

        $this->makeRequest()
            ->assertStatus(200)
            ->assertJson($expectedData);

        $this->assertDatabaseHas('functional_assays',  $this->jsonifyArrays($expectedData));
    }


    /**
     * @test
     */
    public function validates_required_parameters()
    {
        $this->makeRequest([
            'affiliation_id' => null,
            'publication_id' => null,
            'replication' => null,
            'statistical_analysis_description' => null,
            'range_type' => null,
            'assay_class_ids' => null
        ])
            ->assertValidationErrors([
                'affiliation_id' => 'This must have a value.',
                'publication_id' => 'This must have a value.',
                'replication' => 'This must have a value.',
                'statistical_analysis_description' => 'This must have a value.',
                'range_type' => 'This must have a value.',
                'assay_class_ids' => 'This must have a value.',
            ]);
    }

    /**
     * @test
     */
    public function validates_paramter_formats()
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
            'affiliation_id' => 'This must be an integer',
            'publication_id' => 'The selection is invalid',
            'hgnc_id' => 'The format is invalid.',
            'range_type' => 'The selection is invalid.',
            'approved' => 'This must be true or false.',
            'material_used' => 'This must be an array.',
            'patient_derived_material_used' => 'This must be an array',
            'field_notes' => 'This must be an array.'
        ]);
    }

    /**
     * @test
     */
    public function fires_functionalAssayUpdated_event()
    {
        Event::fake();

        $this->makeRequest()
            ->assertStatus(200);

        Event::assertDispatched(FunctionalAssayUpdated::class);
    }
    
    /**
     * @test
     */
    public function fires_functionalAssaySaved_event()
    {
        Event::fake();

        $this->makeRequest()
            ->assertStatus(200);

        Event::assertDispatched(FunctionalAssaySaved::class);
    }
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? $this->getDefaultData();

        return $this->json('PUT', '/api/functional-assays/'.$this->functionalAssay->id, $data);
    }
}
