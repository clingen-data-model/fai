<?php

namespace Tests\Feature\End2End;

use Carbon\Carbon;
use App\Models\Publication;
use App\Models\CodingSystem;
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
        $thirdAssayClass = $this->createAssayClass();

        $data = $expectedData = $this->getDefaultData();

        $publication = Publication::factory()->create(['coding_system_id' => $data['publication']['coding_system_id'], 'code' => 12345]);

        $data['assay_class_ids'] = [1,$thirdAssayClass->id];
        unset($expectedData['assay_class_ids']);
        unset($expectedData['publication']);
        // $expectedData['publication_id'] = $publication->id;
        // $expectedData['publication'] = $publication->toArray();

        $this->makeRequest($data)
            ->assertStatus(200)
            ->assertJson($expectedData);

        $this->assertDatabaseHas('functional_assays',  $this->jsonifyArrays($expectedData));
        $this->assertDatabaseHas('assay_class_functional_assay', [
            'functional_assay_id' => $this->functionalAssay->id,
            'assay_class_id' => 1
        ]);
        $this->assertDatabaseHas('assay_class_functional_assay', [
            'functional_assay_id' => $this->functionalAssay->id,
            'assay_class_id' => $thirdAssayClass->id
        ]);
        $this->assertDatabaseMissing('assay_class_functional_assay', [
            'functional_assay_id' => $this->functionalAssay->id,
            'assay_class_id' => 2
        ]);
    }

    /**
     * @test
     */
    public function updates_functionalAssay_with_additional_publications()
    {
        $data = $expectedData = $this->getDefaultData();

        $publication = Publication::factory()->create(['coding_system_id' => $data['publication']['coding_system_id'], 'code' => 12345]);

        $this->functionalAssay->update(['additional_publication_ids' => [$publication->id]]);

        $data['additional_publications'] = [
            [
                'coding_system_id' => $publication->coding_system_id,
                'code' => $publication->code,
                'title' => $publication->title,
                'author' => $publication->author,
                'year' => $publication->year
            ],
            [
                'coding_system_id' => $data['publication']['coding_system_id'],
                'code' => 666,
                'title' => 'Book of the dead',
                'year' => 1066,
                'author' => 'lucifer morningstar'
            ]
        ];

        $this->makeRequest($data)
            ->assertStatus(200);
            // ->assertJson($expectedData);

        unset($expectedData['additional_publications']);
        $expectedData['additional_publication_ids'] = collect([
            Publication::findBySystemAndCode($publication->coding_system_id, $publication->code),
            Publication::findBySystemAndCode($data['publication']['coding_system_id'], 666),
        ])->pluck('id')->toJson();

        $this->assertDatabaseHas('functional_assays', [
            'id' => $this->functionalAssay->id,
            'additional_publication_ids' => $expectedData['additional_publication_ids']
        ]);
    }



    /**
     * @test
     */
    public function validates_required_parameters()
    {
        $this->makeRequest([
            'affiliation_id' => null,
            'publication' => null,
            'replication' => null,
            'statistical_analysis_description' => null,
            'range_type' => null,
            'assay_class_ids' => null
        ])
            ->assertValidationErrors([
                'affiliation_id' => 'This must have a value.',
                'publication' => 'This must have a value.',
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
            'publication' => [
                'coding_system_id' => 999,
                'code' => str_repeat('X', 256)
            ],
            'hgnc_id' => 'blah',
            'range_type' => 'blah',
            'approved' => 'blah',
            'material_used' => 'blah',
            'patient_derived_material_used' => 'blah',

            'range' => str_repeat("X", 256),
            'normal_range' => str_repeat("X", 256),
            'abnormal_range' => str_repeat("X", 256),
            'indeterminate_range' => str_repeat("X", 256),
            'validation_control_pathogenic' => 'test',
            'validation_control_benign' => 'test',
            'significance_threshold' => str_repeat("X", 256),
            'units' => str_repeat('X', 256),
            'field_notes' => 'beans'
        ])
        ->assertValidationErrors([
            'affiliation_id' => 'This must be an integer',
            'publication.coding_system_id' => 'The selection is invalid',
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
    public function sets_validated_at_if_validated()
    {
        Carbon::setTestNow('2022-07-29');

        $this->makeRequest()
            ->assertStatus(200);

        $this->assertDatabaseHas('functional_assays', [
            'id' => $this->functionalAssay->id,
            'validated_at' => Carbon::now()
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
