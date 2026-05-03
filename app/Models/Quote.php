<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quote_number', 'customer_id', 'contact_id', 'sales_rep_id', 'status',
        'quote_date', 'expiry_date', 'customer_po', 'payment_terms', 'ship_via',
        'shipping_cost', 'tax_rate', 'discount_pct', 'subtotal', 'tax_amount',
        'total', 'notes', 'internal_notes', 'currency', 'probability'
    ];

    protected $casts = [
        'quote_date' => 'date',
        'expiry_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'probability' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->quote_number)) {
                $model->quote_number = 'QT-' . date('Y') . '-' . 
                    str_pad(DB::table('quotes')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines()
    {
        return $this->hasMany(QuoteLine::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'quote_id');
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'sent' => 'primary',
            'won' => 'success',
            'lost' => 'danger',
            'expired' => 'warning'
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}