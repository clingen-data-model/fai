<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Publication;
use App\Models\FunctionalAssay;
use Illuminate\Validation\Rule;
use App\Events\FunctionalAssaySaved;
use Lorisleiva\Actions\ActionRequest;
use App\Events\FunctionalAssayUpdated;
use Lorisleiva\Actions\Concerns\AsController;

class FunctionalAssayUpdate
{
    use AsController;

    public function __construct(private PublicationFindOrCreate $findOrCreatePublication)
    {
    }


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
        $data = $request->safe()->except('publication');
        $data['validated_at'] = Carbon::now();

        $pubData = $request->safe()->publication;

        // Find or create a publication based on input.
        $data['publication_id'] = $this->findOrCreatePublication->handle([
            'coding_system_id' => $pubData['coding_system_id'],
            'code' => $pubData['code']
        ], $pubData)->id;

        if ($request->additional_publications) {
            // Find or create additional publications based on input.
            $data['additional_publication_ids'] = collect($request->additional_publications)
                                                            ->map(function ($pData) {
                                                                return $this->findOrCreatePublication->handle([
                                                                    'coding_system_id' => $pData['coding_system_id'],
                                                                    'code' => $pData['code']
                                                                ], $pData)->id;
                                                            })->toArray();
        }


        return $this->handle($functionalAssay, $data);
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'affiliation_id' => 'filled|int',
            'publication' => 'filled|array',
            'publication.coding_system_id' => 'filled|int|exists:coding_systems,id',
            'publication.code' => 'filled|max:255',
            'publication.title' => 'nullable',
            'publication.year' => 'nullable',
            'publication.author' => 'nullable',
            'additional_publications' => 'nullable|array',
            // 'additional_publications.*' => 'int',
            'hgnc_id' => 'filled|regex:/^HGNC:\d+$/',
            'gene_symbol' => 'nullable|max:255',
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
            'ep_proposed_strength_benign' => 'nullable|max:255',
         ];
    }

    public function authorize(ActionRequest $requeset): bool
    {
        return true;
    }

    private function getPublicationIdForCode($system, $code): int
    {
        $pub = Publication::findBySystemAndCode($system, $code);
        if (!$pub) {
            $pub = $this->createPublication->handle([
                'coding_system_id' => config('publications.coding_systems.'.$system.'.id'),
                'code' => $code
            ]);
        }

        return $pub->id;
    }

}
