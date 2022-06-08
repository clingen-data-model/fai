<?php

namespace App\Actions;

use App\Models\AssayClass;
use Lorisleiva\Actions\Concerns\AsController;

class AssayClassFind
{
    use AsController;

    public function handle(AssayClass $assayClass): AssayClass
    {
        return $assayClass;
    }
    
}