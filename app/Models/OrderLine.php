<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $fillable = [
        'order_id', 'part_id', 'line_number', 'quantity', 'qty_shipped',
        'qty_invoiced', 'unit_cost', 'unit_price', 'discount_pct', 'line_total',
        'status', 'due_date', 'line_notes', 'shop_notes', 'work_order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}