<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptLine extends Model
{
    protected $fillable = [
        'receipt_id', 'po_line_id', 'part_id', 'warehouse_id', 'bin_location_id',
        'quantity', 'unit_cost', 'lot_number', 'date_code', 'revision',
        'serial_number', 'inspection_status', 'qty_accepted', 'qty_rejected',
        'inspection_notes'
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}