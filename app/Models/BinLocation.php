<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinLocation extends Model
{
    protected $fillable = [
        'warehouse_id', 'code', 'description', 'zone', 'aisle',
        'row', 'level', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}