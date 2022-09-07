<?php

namespace App\Models;

use App\Models\Publication;
use Illuminate\Support\Collection;
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
        'additional_publication_ids',
        'hgnc_id',
        'gene_symbol',
        'approved',
        'material_used',
        'patient_derived_material_used',
        'description',
        'additional_publication_ids',
        'read_out_description',
        'range',
        'normal_range',
        'abnormal_range',
        'indeterminate_range',
        'validation_control_pathogenic',
        'validation_control_benign',
        'replication',
        'statistical_analysis_description',
        'significance_threshold',
        'comment',
        'range_type',
        'units',
        'field_notes',
        'assay_notes',
        'ep_biological_replicates',
        'ep_technical_replicates',
        'ep_basic_positive_control',
        'ep_basic_negative_control',
        'ep_proposed_strength_pathogenic',
        'ep_proposed_strength_benign',
        'raw_import_id',
        'validated_at'
    ];

    public $casts = [
        'affiliation_id' => 'integer',
        'publication_id' => 'integer',
        'additional_publication_ids' => 'array',
        'raw_import_id' => 'integer',
        'approved' => 'boolean',
        'field_notes' => 'array',
        'validated_at' => 'datetime',
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

    // SCOPE
    public function scopeIsValidated($query)
    {
        return $query->whereNotNull('validated_at');
    }

    public function scopeNeedsValidation($query)
    {
        return $query->whereNull('validated_at');
    }

    // ACCESSORS
    public function getAdditionalPublicationsAttribute(): Collection
    {
        return Publication::query()->whereIn('id', $this->additional_publication_ids ?? [])->get() ?? [];
    }

}
