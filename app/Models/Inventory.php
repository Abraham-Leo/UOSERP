<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'part_id', 'warehouse_id', 'bin_location_id', 'qty_on_hand',
        'qty_reserved', 'qty_on_order', 'unit_cost'
    ];

    protected $casts = [
        'qty_on_hand' => 'decimal:4',
        'qty_reserved' => 'decimal:4',
        'qty_on_order' => 'decimal:4',
        'unit_cost' => 'decimal:4'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function binLocation()
    {
        return $this->belongsTo(BinLocation::class);
    }

    public function getQtyAvailableAttribute()
    {
        return $this->qty_on_hand - $this->qty_reserved;
    }
}