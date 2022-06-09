<?php

namespace App\Actions;

use App\Models\Publication;
use Lorisleiva\Actions\Concerns\AsController;

class PublicationFind
{
    use AsController;

    public function handle(Publication $publication)
    {
        $publication->load('codingSystem');
        return $publication;
    }
}