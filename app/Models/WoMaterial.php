<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WoMaterial extends Model
{
    protected $table = 'wo_materials';

    protected $fillable = [
        'work_order_id', 'part_id', 'bom_line_id', 'qty_required',
        'qty_picked', 'qty_consumed', 'qty_scrapped', 'unit_cost',
        'status', 'lot_number', 'serial_number', 'bin_location_id'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}