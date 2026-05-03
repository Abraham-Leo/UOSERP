<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wo_number', 'part_id', 'order_id', 'order_line_id', 'bom_id', 'type',
        'status', 'quantity', 'qty_complete', 'qty_scrapped', 'order_date',
        'due_date', 'work_start_date', 'completed_date', 'unit_cost_estimate',
        'unit_cost_actual', 'labor_hrs_estimate', 'labor_hrs_actual',
        'material_cost_actual', 'labor_cost_actual', 'overhead_cost_actual',
        'outsource_cost_actual', 'notes', 'warehouse_id', 'released', 'created_by'
    ];

    protected $casts = [
        'order_date' => 'date',
        'due_date' => 'date',
        'work_start_date' => 'date',
        'completed_date' => 'date',
        'quantity' => 'decimal:4',
        'qty_complete' => 'decimal:4',
        'qty_scrapped' => 'decimal:4',
        'released' => 'boolean',
        'released_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->wo_number)) {
                $model->wo_number = 'WO-' . date('Y') . '-' . 
                    str_pad(DB::table('work_orders')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function bom()
    {
        return $this->belongsTo(Bom::class);
    }

    public function materials()
    {
        return $this->hasMany(WoMaterial::class, 'work_order_id');
    }

    public function operations()
    {
        return $this->hasMany(WoOperation::class, 'work_order_id')->orderBy('sequence');
    }

    public function laborEntries()
    {
        return $this->hasMany(LaborEntry::class, 'work_order_id');
    }

    public function getProgressPctAttribute()
    {
        return $this->quantity > 0 ? round(($this->qty_complete / $this->quantity) * 100) : 0;
    }

    public function getIsLateAttribute()
    {
        return $this->due_date && $this->due_date->isPast() && !in_array($this->status, ['complete', 'cancelled']);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'released', 'in_progress']);
    }
}