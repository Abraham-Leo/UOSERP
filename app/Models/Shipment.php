<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_number', 'order_id', 'customer_id', 'status', 'ship_date',
        'carrier', 'tracking_number', 'service', 'weight', 'dimensions',
        'freight_cost', 'freight_charge', 'notes'
    ];

    protected $casts = [
        'ship_date' => 'date',
        'weight' => 'decimal:4',
        'freight_cost' => 'decimal:2',
        'freight_charge' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->shipment_number)) {
                $model->shipment_number = 'SHP-' . date('Y') . '-' . 
                    str_pad(DB::table('shipments')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}