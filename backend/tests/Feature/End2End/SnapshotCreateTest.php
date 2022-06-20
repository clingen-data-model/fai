<?php

namespace Tests\Feature\End2End;

use Tests\TestCase;
use Illuminate\Testing\TestResponse;
use App\Actions\FunctionalAssayCreate;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\traits\FunctionalAssayTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SnapshotCreateTest extends TestCase
{
    use RefreshDatabase;
    use FunctionalAssayTestHelpers;

    /**
     * @test
     */
    public function snapshot_created_when_functional_assay_created()
    {
        $this->makeCreateRequest()
            ->assertStatus(201);

        $id = \DB::table('functional_assays')->max('id');

        $this->assertDatabaseHas('snapshots', [
            'functional_assay_id' => $id,
            'version' => 1,
            'data->hgnc_id' => 'HGNC:12345'
        ]);
    }
    
    /**
     * @test
     */
    public function snapshot_created_when_functional_assay_updated()
    {
        $data = $this->getDefaultData();
        $assayClassIds = $data['assay_class_ids'];
        $faData = $data;
        $this->functionalAssay = (new FunctionalAssayCreate())->handle($faData, $assayClassIds);

        $data = $this->getDefaultData();
        $data['hgnc_id'] = 'HGNC:98765';
        $this->makeUpdateRequest($data)
            ->assertStatus(200);

        $this->assertDatabaseHas('snapshots', [
            'functional_assay_id' => $this->functionalAssay->id,
            'version' => 2,
            'data->hgnc_id' => 'HGNC:98765'
        ]);
    }

    private function makeCreateRequest($data = null): TestResponse
    {
        $data = $data ?? $this->getDefaultData();

        return $this->json('POST', '/api/functional-assays', $data);
    }

    private function makeUpdateRequest($data = null): TestResponse
    {
        $data = $data ?? $this->getDefaultData();

        return $this->json('PUT', '/api/functional-assays/'.$this->functionalAssay->id, $data);
    }
    
    
}
