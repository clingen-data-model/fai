<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawImport extends Model
{
    use HasFactory;

    public $fillable = [
        'assay_class',
        'affiliation_id',
        'publication_id',
        'pubmed_id',
        'gene_symbol',
        'approved',
        'data',
    ];

    public $casts = [
        'id' => 'integer',
        'affiliation_id' => 'integer',
        'pubmed_id' => 'integer',
        'approved' => 'boolean',
        'data' => 'array'
    ];
    
    
}
