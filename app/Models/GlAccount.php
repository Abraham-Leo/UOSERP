<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlAccount extends Model
{
    protected $fillable = [
        'account_number', 'name', 'type', 'sub_type', 'is_active',
        'balance', 'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:2'
    ];

    public function entries()
    {
        return $this->hasMany(GlEntry::class);
    }
}