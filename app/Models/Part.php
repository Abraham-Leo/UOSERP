<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'part_number', 'description', 'category', 'type', 'unit_of_measure',
        'unit_cost', 'standard_cost', 'last_cost', 'average_cost', 'unit_price',
        'weight', 'weight_unit', 'lead_time_days', 'reorder_point',
        'economic_order_qty', 'safety_stock', 'preferred_vendor_id', 'make_buy',
        'is_active', 'is_purchaseable', 'is_saleable', 'is_manufactured',
        'track_serial', 'track_lot', 'revision', 'notes', 'custom_fields'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:4',
        'standard_cost' => 'decimal:4',
        'last_cost' => 'decimal:4',
        'average_cost' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'is_active' => 'boolean',
        'is_purchaseable' => 'boolean',
        'is_saleable' => 'boolean',
        'is_manufactured' => 'boolean',
        'track_serial' => 'boolean',
        'track_lot' => 'boolean',
        'custom_fields' => 'json'
    ];

    public function boms()
    {
        return $this->hasMany(Bom::class, 'parent_part_id');
    }

    public function currentBom()
    {
        return $this->hasOne(Bom::class, 'parent_part_id')
                    ->where('is_current', true)
                    ->latest();
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function poLines()
    {
        return $this->hasMany(PoLine::class);
    }

    public function getQtyOnHandAttribute()
    {
        return $this->inventory->sum('qty_on_hand');
    }

    public function getQtyAvailableAttribute()
    {
        return $this->inventory->sum('qty_on_hand') - $this->inventory->sum('qty_reserved');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('part_number', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
    }
}