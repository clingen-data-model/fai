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
        return FunctionalAssay::query()
                ->select(['id', 'publication_id', 'gene_symbol', 'approved', 'hgnc_id'])
                ->with([
                    'assayClasses' => function ($q) {$q->select(['assay_classes.id', 'name', 'description']);}, 
                    'publication' => function ($q) { $q->select(['publications.id', 'title', 'coding_system_id', 'code']);}
                ])
                ->get(20);
    }    
}