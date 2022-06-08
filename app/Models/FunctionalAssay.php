<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FunctionalAssay extends Model
{
    use HasFactory;

    public $fillable = [
        'affiliation_id',
        'publication_id',
        'hgnc_id',
        'approved',
        'material_used',
        'patient_derived_material_used',
        'description',
        // additional_document: Optional[List[str]] = None
        'read_out_description',
        'range',
        'normal_range',
        'abnormal_range',
        'indeterminate_range',
        'validation_control_pathogenic',
        'validation_control_benign',
        'replication',
        'statistical_analysis_description',
        'significance_treshold',
        'comment',
        'range_type',
        'units',
        'field_notes',
        'assay_notes'
    ];

    public $casts = [
        'affiliation_id' => 'integer',
        'publication_id' => 'integer',
        'approved' => 'boolean',
        'material_used' => 'array',
        'patient_derived_material_used' => 'array',
        'field_nots' => 'array'
    ];
    
    /**
     * The assayClasses that belong to the FunctionalAssay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assayClasses(): BelongsToMany
    {
        return $this->belongsToMany(AssayClass::class);
    }

    /**
     * Get the publication that owns the FunctionalAssay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Get all of the snapshots for the FunctionalAssay
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function snapshots(): HasMany
    {
        return $this->hasMany(Snapshot::class);
    }
}
