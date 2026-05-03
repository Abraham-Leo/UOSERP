<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaborEntry extends Model
{
    protected $fillable = [
        'work_order_id', 'wo_operation_id', 'user_id', 'clock_in',
        'clock_out', 'hours', 'overtime_hours', 'labor_rate', 'labor_cost', 'notes'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'hours' => 'decimal:4',
        'labor_rate' => 'decimal:4',
        'labor_cost' => 'decimal:4'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}