<?php

namespace App\Actions;

use App\Models\AssayClass;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class AssayClassCreate
{
    use AsController;
    
    public function handle(array $data): AssayClass
    {
        $assayClass = AssayClass::make($data);
        $assayClass->save();

        return $assayClass;
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->only(['name', 'description']));
    }

    public function rules(): array
    {
        return [
           'name' => 'required|max:255|unique:assay_classes,name',
        ];
    }
    
}
