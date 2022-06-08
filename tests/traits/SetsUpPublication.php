<?php

namespace Tests\traits;

use App\Models\Publication;
use App\Models\CodingSystem;
use Tests\traits\SetsUpCodingSystem as TraitsSetsUpCodingSystem;

/**
 * Methods for setting up CodingSystem
 */
trait SetsUpCodingSystem 
{
    use TraitsSetsUpCodingSystem;

    public function setUpPublication()
    {
        $this->setupCodingSystem();
        $this->publication = $this->createPublication([
            'coding_systm_id' => $this->codingSystemId
        ]);
    }

    protected function createPublication($data = null)
    {
        return Publication::factory()->create($data);
    }
    
}
