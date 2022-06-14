<?php

namespace Tests\traits;

use App\Models\CodingSystem;

/**
 * Methods for setting up CodingSystem
 */
trait SetsUpCodingSystem
{
    public function setUpSetsUpCodingSystem()
    {
        $this->codingSystem = $this->createCodingSystem();
    }

    protected function createCodingSystem($data = null)
    {
        return CodingSystem::factory()->create($data);
    }
    
}
