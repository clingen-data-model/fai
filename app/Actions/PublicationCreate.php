<?php

namespace App\Actions;

use App\Models\Publication;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class PublicationCreate
{
    	use AsController;

    public function handle(array $data)
    {
        return Publication::create($data);
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->only(['coding_system_id', 'code']));
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'coding_system_id' => ['required', 'exists:coding_systems,id'],
            'code' => [ 
                        'required', 
                        'max:255',
                        Rule::unique('publications', 'code')
                            ->where(function ($q) use ($request) { 
                                $q->where('coding_system_id', $request->coding_system_id);
                            })
                    ]
        ];
    }

    public function authorize(ActionRequest $requeset):bool
    {
        return true;
    }

}