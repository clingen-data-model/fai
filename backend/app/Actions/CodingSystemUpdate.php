<?php

namespace App\Actions;

use App\Models\CodingSystem;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class CodingSystemUpdate
{
    use AsController;

    public function handle(CodingSystem $codingSystem, $data): CodingSystem
    {
        $codingSystem->update($data);
        return $codingSystem;
    }

    public function asController(ActionRequest $request, CodingSystem $codingSystem)
    {
        return $this->handle($codingSystem, $request->validated());
    }

    public function rules(ActionRequest $request): array
    {
        return [
           'name' => [
                        'required',
                        'max:255',
                        Rule::unique('coding_systems', 'name')
                            ->ignore($request->codingSystem)
                    ],
        ];
    }
    
}
