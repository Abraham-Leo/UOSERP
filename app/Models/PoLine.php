<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoLine extends Model
{
    protected $fillable = [
        'purchase_order_id', 'part_id', 'line_number', 'quantity',
        'qty_received', 'qty_billed', 'unit_cost', 'line_total',
        'commit_date', 'status', 'vendor_part_number', 'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'qty_received' => 'decimal:4',
        'qty_billed' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'line_total' => 'decimal:2',
        'commit_date' => 'date'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}