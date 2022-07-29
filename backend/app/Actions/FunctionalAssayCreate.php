<?php

namespace App\Actions;
use App\Models\FunctionalAssay;
use Illuminate\Validation\Rule;
use App\Events\FunctionalAssaySaved;
use Lorisleiva\Actions\ActionRequest;
use App\Events\FunctionalAssayCreated;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsController;

class FunctionalAssayCreate
{
    	use AsController;

    public function handle(array $functionalAssayData, array $assayClassIds)
    {
        $funcAssay = FunctionalAssay::create($functionalAssayData);
        foreach ($assayClassIds as $id) {
            $funcAssay->assayClasses()
                ->attach($id);
        }

        event(new FunctionalAssayCreated($funcAssay));
        event(new FunctionalAssaySaved($funcAssay));

        return $funcAssay;
    }

    public function asController(ActionRequest $request)
    {
        $funcAssayData = $request->safe()->except('assay_class_ids');
        $funcAssayData['validated_at'] = Carbon::now();

        $assayClassIds = $request->safe()->only('assay_class_ids');
        $functionalAssay = $this->handle($funcAssayData, $assayClassIds);
        $functionalAssay->load('assayClasses');

        return $functionalAssay;
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'affiliation_id' => 'required|int',
            'publication_id' => 'required|int|exists:publications,id',
            'hgnc_id' => 'required|regex:/^HGNC:\d+$/',
            'approved' => 'nullable|boolean',
            'material_used' => 'nullable',
            'patient_derived_material_used' => 'nullable',
            'description' => 'nullable',
            'read_out_description' => 'nullable',
            'range' => 'nullable|max:255',
            'normal_range' => 'nullable|max:255',
            'abnormal_range' => 'nullable|max:255',
            'indeterminate_range' => 'nullable|max:255',
            'validation_control_pathogenic' => 'nullable|int', //TODO: Should be numeric
            'validation_control_benign' => 'nullable|int', //TODO: Should be numeric
            'replication' => 'required',
            'statistical_analysis_description' => 'required',
            'significance_threshold' => 'nullable|max:255',
            'comment' => 'nullable',
            'range_type' => ['required',Rule::in(['qualitative', 'quantitative'])],
            'units' => 'nullable|max:255',
            'field_notes' => 'nullable|array',
            'assay_notes' => 'nullable',
            'assay_class_ids' => 'required|array',
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
