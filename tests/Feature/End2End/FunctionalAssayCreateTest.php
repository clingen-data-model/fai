<?php

namespace Tests\Feature\End2End;

use Tests\Feature\End2End\TestCase;
use Tests\traits\SetsUpFunctionalAssay;
use Illuminate\Testing\TestResponse;
use Tests\traits\FunctionalAssayTestHelpers;

class FunctionalAssayCreateTest extends TestCase
{
    use FunctionalAssayTestHelpers;

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
    
    

    private function makeRequest($data = null): TestResponse
    {
        $data = $data ?? $this->getDefaultData();

        return $this->json('POST', '/api/functional-assays', $data);
    }

    private function getDefaultData(): array
    {
        return [
            'affiliation_id' => 50001,
            'publication_id' => $this->publication->id,
            'hgnc_id' => 'HGNC:12345',
            'approved' => false,
            'material_used' => ['a', 'b', 'c'],
            'patient_derived_material_used' => ['a1', 'b1', 'c1'],
            'description' => 'This is a description',
            //  => additional_document: Optional[List[str]] = None
            'read_out_description' => 'read out description',
            'range' => 'a to g',
            'normal_range' => 'a - c',
            'abnormal_range' => 'f - g',
            'indeterminate_range' => 'd - e',
            'validation_control_pathogenic' => 'validation control pathogenic',
            'validation_control_benign' => 'validation control benign',
            'replication' => 'replication',
            'statistical_analysis_description' => 'statistical_analysis_description',
            'significance_threshold' => 'significance_threshold',
            'comment' => 'comment',
            'range_type' => 'quantitative',
            'units' => 'units',
            'field_notes' => ["notes" => "test"],
            'assay_notes' =>'assay_notes',
            'assay_class_ids' => [1, 2]
        ];
    }
    
    
    
}
