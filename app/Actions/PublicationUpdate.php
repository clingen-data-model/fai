<?php

namespace App\Actions;
use App\Models\Publication;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class PublicationUpdate
{
    	use AsController;

    public function handle(Publication $publication, $newData)
    {
        $publication->update($newData);

        // dd($publication->toArray());

        return $publication;
    }

    public function asController(ActionRequest $request, Publication $publication)
    {
        return $this->handle($publication, $request->validated());
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'code' => [
                'required', 
                'max:255', 
                Rule::unique('publications', 'code')
                ->where(function ($q) use ($request) { 
                    $q->where('coding_system_id', $request->coding_system_id);
                })->ignore($request->publication),
            ],
            'coding_system_id' => [
                'required',
                'exists:coding_systems,id',
            ],
            'title' => ['nullable','max:255']
];
    }

    public function authorize(ActionRequest $requeset):bool
    {
        return true;
    }

}