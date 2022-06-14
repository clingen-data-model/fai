<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssayClass extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'name',
        'description'
    ];

    public $casts = [
        'id' => 'integer'
    ];
    
    /**
     * RELATIONS
     */

    /**
     * Get all of the functionalAssays for the AssayClass
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function functionalAssays(): BelongsToMany
    {
        return $this->belongsToMany(FunctionalAssay::class);
    }

    /**
     * SCOPES
     */
    
    public function scopeOrLikeName($query, $name)
    {
        return $query->orWhere('name', 'LIKE', '%'.$name.'%');
    }
    
}
