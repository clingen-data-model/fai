<?php

namespace App\Actions;
use App\Models\FunctionalAssay;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class FunctionalAssayUpdate
{
    	use AsController;

    public function handle(FunctionalAssay $functionalAssay, $newData)
    {
        $functionalAssay->update($newData);

        return $functionalAssay;
    }

    public function asController(ActionRequest $request, FunctionalAssay $functionalAssay)
    {
        return $this->handle($functionalAssay, $request->validated());
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'affiliation_id' => 'filled|int',
            'publication_id' => 'filled|int|exists:publications,id',
            'hgnc_id' => 'filled|regex:/^HGNC:\d+$/',
            'approved' => 'nullable|boolean',
            'material_used' => 'nullable|array',
            'patient_derived_material_used' => 'nullable|array',
            'description' => 'nullable',
            'read_out_description' => 'nullable',
            'range' => 'nullable|max:255',
            'normal_range' => 'nullable|max:255',
            'abnormal_range' => 'nullable|max:255',
            'indeterminate_range' => 'nullable|max:255',
            'validation_control_pathogenic' => 'nullable|max:255',
            'validation_control_benign' => 'nullable|max:255',
            'replication' => 'filled',
            'statistical_analysis_description' => 'filled',
            'significance_threshold' => 'nullable|max:255',
            'comment' => 'nullable',
            'range_type' => ['filled',Rule::in(['qualitative', 'quantitative'])],
            'units' => 'nullable|max:255',
            'field_notes' => 'nullable|array',
            'assay_notes' => 'nullable',
            'assay_class_ids' => 'filled|array',
            'assay_class_ids.*' => 'exists:assay_classes,id'
         ];
    }

    public function authorize(ActionRequest $requeset):bool
    {
        return true;
    }

}