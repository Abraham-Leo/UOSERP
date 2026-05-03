<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Eco extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'eco_number', 'title', 'type', 'status', 'description', 'risk_mitigation',
        'cost_impact', 'part_id', 'rev_from', 'rev_to', 'initiated_by',
        'assigned_to', 'due_date', 'approved_at', 'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'cost_impact' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->eco_number)) {
                $model->eco_number = 'ECO-' . date('Y') . '-' . 
                    str_pad(DB::table('ecos')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}