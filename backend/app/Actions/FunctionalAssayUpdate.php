<?php

namespace App\Actions;

use App\Models\FunctionalAssay;
use Illuminate\Validation\Rule;
use App\Events\FunctionalAssaySaved;
use Lorisleiva\Actions\ActionRequest;
use App\Events\FunctionalAssayUpdated;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsController;

class FunctionalAssayUpdate
{
    	use AsController;

    public function handle(FunctionalAssay $functionalAssay, $newData)
    {
        $assayClassIds = $newData['assay_class_ids'];
        $functionalAssay->update($newData);

        $functionalAssay->assayClasses()->sync($assayClassIds);

        event(new FunctionalAssayUpdated($functionalAssay));
        event(new FunctionalAssaySaved($functionalAssay));

        return $functionalAssay;
    }

    public function asController(ActionRequest $request, FunctionalAssay $functionalAssay)
    {
        $data = $request->validated();
        $data['validated_at'] = Carbon::now();

        return $this->handle($functionalAssay, $data);
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'affiliation_id' => 'filled|int',
            'publication_id' => 'filled|int|exists:publications,id',
            'hgnc_id' => 'filled|regex:/^HGNC:\d+$/',
            'approved' => 'nullable|boolean',
            'material_used' => 'nullable',
            'patient_derived_material_used' => 'nullable',
            'description' => 'nullable',
            'read_out_description' => 'nullable',
            'range' => 'nullable|max:255',
            'normal_range' => 'nullable|max:255',
            'abnormal_range' => 'nullable|max:255',
            'indeterminate_range' => 'nullable|max:255',
            'validation_control_pathogenic' => 'nullable|int',
            'validation_control_benign' => 'nullable|int',
            'replication' => 'filled',
            'statistical_analysis_description' => 'filled',
            'significance_threshold' => 'nullable|max:255',
            'comment' => 'nullable',
            'range_type' => ['filled',Rule::in(['qualitative', 'quantitative'])],
            'units' => 'nullable|max:255',
            'field_notes' => 'nullable|array',
            'assay_notes' => 'nullable',
            'assay_class_ids' => 'filled|array',
            'assay_class_ids.*' => 'exists:assay_classes,id',
            'ep_biological_replicates' => 'nullable',
            'ep_technical_replicates' => 'nullable',
            'ep_basic_positive_control' => 'nullable',
            'ep_basic_negative_control' => 'nullable',
            'ep_proposed_strength_pathogenic' => 'nullable|max:255',
            'ep_propsed_strength_benign' => 'nullable|max:255',
         ];
    }

    public function authorize(ActionRequest $requeset):bool
    {
        return true;
    }

}
