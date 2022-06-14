<?php

namespace Tests\traits;

use App\Models\AssayClass;

/**
 * Methods for setting up AssayClass
 */
trait SetsUpAssayClass
{
    public function setUpSetsUpAssayClass()
    {
        $this->assayClass = $this->createAssayClass();
    }

    protected function createAssayClass($data = null)
    {
        return AssayClass::factory()->create($data);
    }
    
}
