<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;  // <-- TAMBAHKAN INI

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_number', 'name', 'legal_name', 'email', 'phone', 'fax',
        'website', 'tax_id', 'vat_number', 'payment_terms', 'currency',
        'credit_limit', 'credit_hold', 'discount_pct', 'price_level',
        'billing_address', 'billing_address2', 'billing_city', 'billing_state',
        'billing_zip', 'billing_country', 'shipping_address', 'shipping_address2',
        'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country',
        'industry', 'source', 'status', 'is_active', 'notes', 'parent_customer_id'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'credit_hold' => 'boolean',
        'discount_pct' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->customer_number)) {
                $max = DB::table('customers')->max('customer_number');  // <-- SEKARANG BISA
                $next = $max ? intval(substr($max, -6)) + 1 : 1;
                $model->customer_number = 'CUST-' . str_pad($next, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relasi yang dipanggil di model lain
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function rmas()
    {
        return $this->hasMany(Rma::class);
    }

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function getFullAddressAttribute()
    {
        $address = $this->billing_address;
        if ($this->billing_city) $address .= ", {$this->billing_city}";
        if ($this->billing_state) $address .= ", {$this->billing_state}";
        if ($this->billing_zip) $address .= " {$this->billing_zip}";
        return $address;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('customer_number', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
    }
}