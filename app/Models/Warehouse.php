<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'code', 'name', 'address1', 'city', 'state', 'zip', 'country',
        'is_default', 'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function binLocations()
    {
        return $this->hasMany(BinLocation::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}