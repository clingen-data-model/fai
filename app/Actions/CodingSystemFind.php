<?php

namespace App\Actions;

use App\Models\AssayClass;
use App\Models\CodingSystem;
use Lorisleiva\Actions\Concerns\AsController;

class CodingSystemFind
{
    use AsController;

    public function handle(CodingSystem $codingSystem): CodingSystem
    {
        return $codingSystem;
    }
    
}