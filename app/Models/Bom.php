<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_part_id', 'revision', 'status', 'description',
        'labor_estimate_hours', 'overhead_rate', 'is_current',
        'effective_date', 'created_by'
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'effective_date' => 'datetime',
        'labor_estimate_hours' => 'decimal:4'
    ];

    public function parentPart()
    {
        return $this->belongsTo(Part::class, 'parent_part_id');
    }

    public function lines()
    {
        return $this->hasMany(BomLine::class);
    }

    public function operations()
    {
        return $this->hasMany(BomOperation::class)->orderBy('sequence');
    }

    public function calculateCost()
    {
        return $this->lines->sum(fn($line) => $line->quantity * $line->part->standard_cost);
    }
}