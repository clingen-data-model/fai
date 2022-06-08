<?php

namespace App\Actions;

use App\Models\CodingSystem;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class CodingSystemList
{
    use AsController;

    public function handle(ActionRequest $request): Collection
    {
        $query = CodingSystem::query();

        if ($request->filter) {
            $query->filter($request->filter);
        }

        return $query->get();
    }
    
}
