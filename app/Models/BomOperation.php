<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomOperation extends Model
{
    protected $fillable = [
        'bom_id', 'sequence', 'operation_name', 'work_center',
        'setup_time_hrs', 'run_time_hrs', 'work_instructions',
        'outsource', 'outsource_vendor', 'outsource_lead_days', 'machine_setup'
    ];

    protected $casts = [
        'setup_time_hrs' => 'decimal:4',
        'run_time_hrs' => 'decimal:4',
        'outsource' => 'boolean',
        'machine_setup' => 'boolean'
    ];

    public function bom()
    {
        return $this->belongsTo(Bom::class);
    }
}