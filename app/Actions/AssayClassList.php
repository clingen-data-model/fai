<?php

namespace App\Actions;

use App\Models\AssayClass;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class AssayClassList
{
    use AsController;

    public function handle(ActionRequest $request): Collection
    {
        $query = AssayClass::query();

        if ($request->filter) {
            $query->orlikeName($request->filter);
        }

        return $query->get();
    }
    
}
