<?php

namespace App\Models;

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

    /**
     * ACCESSORS
     */

         public function getNameAttribute()
         {
             if (isset($this->attributes['title'])) {
                return $this->attributes['title'];
             }

             return $this->codingSystem->name.':'.$this->code;
         }
     
}
