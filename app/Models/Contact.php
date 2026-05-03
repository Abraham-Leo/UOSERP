<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'salutation', 'first_name', 'last_name', 'title',
        'email', 'phone', 'mobile', 'fax', 'department', 'is_primary',
        'is_billing', 'is_shipping', 'notes', 'birth_date', 'hobby'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_billing' => 'boolean',
        'is_shipping' => 'boolean',
        'birth_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}