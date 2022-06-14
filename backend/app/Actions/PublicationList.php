<?php

namespace App\Actions;
use App\Models\Publication;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class PublicationList
{
    	use AsController;

    public function handle(ActionRequest $request)
    {
        return Publication::with('codingSystem')->get();
    }    
}