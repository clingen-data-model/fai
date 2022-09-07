<?php

namespace Tests\Feature\End2End;

use Carbon\Carbon;
use Tests\Feature\End2End\TestCase;
use App\Events\FunctionalAssaySaved;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Event;
use App\Events\FunctionalAssayCreated;
use App\Models\Publication;
use Tests\traits\SetsUpFunctionalAssay;
use Tests\traits\FunctionalAssayTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FunctionalAssayCreateTest extends TestCase
{
    use FunctionalAssayTestHelpers;
    use RefreshDatabase;

    public function setup(): void
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
        unset($expected['publication']);

        $this->makeRequest()
            ->assertStatus(201)
            ->assertJson($expected);

        $expected['publication_id'] = Publication::orderBy('id', 'desc')->first()->id;

        $this->assertDatabaseHas('functional_assays', $this->jsonifyArrays($expected));
    }

    /**
     * @test
     */
    public function creates_FunctionalAssay_with_additional_publications()
    {
        $expected = $data = $this->getDefaultData();
        unset($expected['assay_class_ids']);
        unset($expected['publication']);

        $data['additional_publications'] = [
            [
                'coding_system_id' => $this->codingSystem->id,
                'code' => '999',
                'title' => 'Beans for lunch',
                'year' => '1977',
                'author' => 'Bobb D'
            ],
            [
                'coding_system_id' => $this->codingSystem->id,
                'code' => 1234,
                'title' => 'A slow regard for silent things',
                'year' => 2016,
                'author' => 'Ruthfuss P'
            ]
        ];


        $this->makeRequest($data)
            ->assertStatus(201)
            ->assertJson($expected);

        $pubs = collect([
            Publication::findBySystemAndCode($this->codingSystem, '999'),
            Publication::findBySystemAndCode($this->codingSystem, '1234')
        ]);

        $this->assertDatabaseHas('functional_assays', [
            'additional_publication_ids' => json_encode($pubs->pluck('id')->toArray())
        ]);
    }


    /**
     * @test
     */
    public function sets_validated_at_if_validated()
    {
        Carbon::setTestNow('2022-07-29');

        $this->makeRequest()
            ->assertStatus(201);

        $this->assertDatabaseHas('functional_assays', [
            'validated_at' => Carbon::now()
        ]);
    }


    /**
     * @test
     */
    public function validates_required_parameters()
    {
        $this->makeRequest([])
            ->assertValidationErrors([
                'affiliation_id' => 'This is required.',
                'publication.coding_system_id' => 'This is required.',
                'publication.code' => 'This is required.',
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
            'publication' => [
                'coding_system_id' => 999,
                'code' => str_repeat("X", 256),
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
            'validation_control_pathogenic' => str_repeat("X", 256),
            'validation_control_benign' => str_repeat("X", 256),
            'significance_threshold' => str_repeat("X", 256),
            'units' => str_repeat('X', 256),
            'ep_proposed_strength_pathogenic' => str_repeat('X', 256),
            'ep_proposed_strength_benign' => str_repeat('X', 256),
            'field_notes' => 'beans'
        ])
        ->assertValidationErrors([
            'affiliation_id' => 'This must be an integer.',
            'publication.coding_system_id' => 'The selection is invalid.',
            'publication.code' => 'This must not be greater than 255 characters.',
            'hgnc_id' => 'The format is invalid.',
            'range_type' => 'The selection is invalid.',
            'approved' => 'This must be true or false.',
            'field_notes' => 'This must be an array.',
            'validation_control_pathogenic' => 'This must be an integer.',
            'validation_control_benign' => 'This must be an integer.',
            'range' => 'This must not be greater than 255 characters.',
            'normal_range' => 'This must not be greater than 255 characters.',
            'abnormal_range' => 'This must not be greater than 255 characters.',
            'indeterminate_range' => 'This must not be greater than 255 characters.',
            'significance_threshold' => 'This must not be greater than 255 characters.',
            'units' => 'This must not be greater than 255 characters.',
            'ep_proposed_strength_pathogenic' => 'This must not be greater than 255 characters.',
            'ep_proposed_strength_benign' => 'This must not be greater than 255 characters.',
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
