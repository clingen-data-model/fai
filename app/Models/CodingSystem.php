<?php

namespace App\Models;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CodingSystem extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    public $casts = [
        'id' => 'integer'
    ];
    
    /**
     * Get all of the publications for the CodingSystem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
