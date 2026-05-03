<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WoOperation extends Model
{
    protected $table = 'wo_operations';

    protected $fillable = [
        'work_order_id', 'sequence', 'operation_name', 'work_center',
        'setup_time_est', 'run_time_est', 'setup_time_actual', 'run_time_actual',
        'status', 'work_instructions', 'outsource', 'assigned_to',
        'started_at', 'completed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'outsource' => 'boolean'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}