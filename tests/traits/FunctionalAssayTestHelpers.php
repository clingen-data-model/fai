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
    
}
