<?php

namespace App\Models;

use App\Models\CodingSystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'coding_system_id',
        'code',
        'doi',
        'year',
        'author'
    ];

    public $casts = [
        'id' => 'integer'
    ];

    public $with = [
        'codingSystem'
    ];

    public $appends = [
        'name'
    ];

    /**
     * Get the codingSystem that owns the Publication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function codingSystem(): BelongsTo
    {
        return $this->belongsTo(CodingSystem::class);
    }

    /**
     * Get all of the functionalAssays for the Publication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function functionalAssays(): HasMany
    {
        return $this->hasMany(FunctionalAssay::class);
    }

    // ACCESSORS
    public function getNameAttribute()
    {
        if (isset($this->attributes['title'])) {
            return $this->attributes['title'];
        }

        return $this->codingSystem->name.':'.$this->code;
    }

    // REPOSITORY METHODS

    /**
     * Finds a publication based on it's coding system and systen id (i.e. code)
     *
     * @param mixed $system Coding System ID, kebab-cased name, or numeric id.
     * @param string $code Code used to identify the publication within the coding system.
     */
    public static function findBySystemAndCode($system, string $code): ?Publication
    {
        $codingSystemId = $system;
        if (is_object($system) && get_class($system) == CodingSystem::class) {
            $codingSystemId = $system->id;
        }
        if (is_string($system)) {
            $codingSystemId = config('publications.coding_systems.'.$system.'.id');
        }

        return static::query()
                ->where(['coding_system_id' => $codingSystemId, 'code' => $code])
                ->first();
    }
}
