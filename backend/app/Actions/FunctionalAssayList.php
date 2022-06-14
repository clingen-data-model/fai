<?php

namespace App\Actions;
use App\Models\FunctionalAssay;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class FunctionalAssayList
{
    	use AsController;

    public function handle(ActionRequest $request)
    {
        return FunctionalAssay::with('assayClasses')->get();
    }    
}