<?php

namespace App\Actions;

use App\Models\CodingSystem;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class CodingSystemCreate
{
    use AsController;
    
    public function handle(array $data): CodingSystem
    {
        $codingSystem = CodingSystem::make($data);
        $codingSystem->save();

        return $codingSystem;
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->only('name'));
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
