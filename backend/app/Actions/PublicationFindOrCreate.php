<?php

namespace App\Actions;

use App\Models\Publication;

class PublicationFindOrCreate
{


    public function handle(array $matchData, array $storeData): Publication
    {
        $pub = Publication::firstOrCreate($matchData, $storeData);
        return $pub;
    }
}
