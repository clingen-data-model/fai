<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    use HasFactory;

    public $fillable = [
        'functional_assay_id',
        'version',
        'data'
    ];

    public $casts = [
        'id' => 'integer',
        'functional_assay_id' => 'integer',
        'version' => 'integer',
        'data' => 'array'
    ];
    
    
}
