<?php

namespace App\Actions;

use App\Models\FunctionalAssay;
use Lorisleiva\Actions\Concerns\AsController;

class FunctionalAssayFind
{
    use AsController;

    public function handle(FunctionalAssay $functionalAssay)
    {
        $functionalAssay->load('assayClasses', 'publication');
        $functionalAssay->additional_publications = $functionalAssay->getAdditionalPublicationsAttribute();
        return $functionalAssay;
    }
}
