<?php

namespace Tests\traits;

use App\Models\FunctionalAssay;
use Tests\traits\SetsUpPublication;

/**
 * Methods for setting up CodingSystem
 */
trait FunctionalAssayTestHelpers 
{
    use SetsUpPublication;
    use SetsUpAssayClass;

    public function setUpFunctionalAssayTestHelpers()
    {
        $this->setUpSetsUpPublication();
        $this->setUpSetsUpAssayClass();
        $this->functionalAssay = $this->createFunctionalAssay();
    }

    protected function createFunctionalAssay($data = null)
    {
        $data = $data ?? [
            'publication_id' => $this->publication->id
        ];
        return FunctionalAssay::factory()->create($data);
    }

    
    private function getDefaultData(): array
    {
        return [
            'affiliation_id' => 50001,
            'publication_id' => $this->publication->id,
            'hgnc_id' => 'HGNC:12345',
            'approved' => false,
            'material_used' => 'a b c d',
            'patient_derived_material_used' => 'a1 b1 c1',
            'description' => 'This is a description',
            'read_out_description' => 'read out description',
            'range' => 'a to g',
            'normal_range' => 'a - c',
            'abnormal_range' => 'f - g',
            'indeterminate_range' => 'd - e',
            'validation_control_pathogenic' => 1,
            'validation_control_benign' => 2,
            'replication' => 'replication',
            'statistical_analysis_description' => 'statistical_analysis_description',
            'significance_threshold' => 'significance_threshold',
            'comment' => 'comment',
            'range_type' => 'quantitative',
            'units' => 'units',
            'field_notes' => ["notes" => "test"],
            'assay_notes' =>'assay_notes',
            'assay_class_ids' => [1, 2],
            'ep_biological_replicates' => 'some text',
            'ep_technical_replicates' => 'some text',
            'ep_basic_positive_control' => 'some text',
            'ep_basic_negative_control' => 'some text',
            'ep_proposed_strength_pathogenic' => 'some text',
            'ep_propsed_strength_benign' => 'some text',
        ];
    }
    
}
