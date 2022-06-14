<?php

namespace App\Actions;

use App\Models\AssayClass;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class AssayClassUpdate
{
    use AsController;

    public function handle(AssayClass $assayClass, $data): AssayClass
    {
        $assayClass->update($data);
        return $assayClass;
    }

    public function asController(ActionRequest $request, AssayClass $assayClass)
    {
        return $this->handle($assayClass, $request->validated());
    }

    public function rules(ActionRequest $request): array
    {
        return [
           'name' => [
                        'required',
                        'max:255',
                        Rule::unique('assay_classes', 'name')
                            ->ignore($request->assayClass)
                    ],
            'description' => 'present'
        ];
    }
    
}
