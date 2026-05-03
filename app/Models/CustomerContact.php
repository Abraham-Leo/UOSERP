<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $fillable = [
        'customer_id', 'name', 'title', 'email', 'phone', 'mobile',
        'primary_contact', 'billing_contact', 'shipping_contact', 'notes'
    ];

    protected $casts = [
        'primary_contact' => 'boolean',
        'billing_contact' => 'boolean',
        'shipping_contact' => 'boolean'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}