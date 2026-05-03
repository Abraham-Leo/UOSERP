<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_number', 'name', 'email', 'phone', 'website',
        'billing_address1', 'billing_city', 'billing_state', 'billing_zip',
        'billing_country', 'payment_terms', 'currency', 'taxable', 'tax_id',
        'vat_id', 'fob', 'ship_via', 'minimum_order', 'buyer_id', 'on_hold',
        'hold_notes', 'rating', 'is_active', 'notes'
    ];

    protected $casts = [
        'taxable' => 'boolean',
        'on_hold' => 'boolean',
        'is_active' => 'boolean',
        'minimum_order' => 'decimal:2',
        'rating' => 'decimal:2'
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}