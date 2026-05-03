<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_id', 'name', 'type', 'serial_number', 'model_number',
        'manufacturer', 'purchase_order_id', 'purchase_value', 'purchase_date',
        'bin_location', 'owner', 'status', 'next_maintenance_date',
        'maintenance_frequency_days', 'notes', 'is_active'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'next_maintenance_date' => 'date',
        'purchase_value' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}