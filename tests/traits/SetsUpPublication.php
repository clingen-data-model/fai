<?php

namespace Tests\traits;

use App\Models\Publication;
use App\Models\CodingSystem;
use Tests\traits\SetsUpCodingSystem as TraitsSetsUpCodingSystem;

/**
 * Methods for setting up CodingSystem
 */
trait SetsUpPublication 
{
    use TraitsSetsUpCodingSystem;

    public function setUpSetsUpPublication()
    {
        $this->setupSetsUpCodingSystem();
        $this->publication = $this->createPublication();
    }

    protected function createPublication($data = null)
    {
        $data = $data ?? [
            'coding_system_id' => $this->codingSystem->id
        ];
        return Publication::factory()->create($data);
    }
    
}
