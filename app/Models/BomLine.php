<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomLine extends Model
{
    protected $fillable = [
        'bom_id', 'part_id', 'quantity', 'unit_of_measure', 'sort_order',
        'reference_designator', 'notes', 'is_phantom', 'substitute_allowed'
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'is_phantom' => 'boolean',
        'substitute_allowed' => 'boolean'
    ];

    public function bom()
    {
        return $this->belongsTo(Bom::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}