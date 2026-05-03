<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number', 'customer_id', 'order_id', 'status', 'invoice_date',
        'due_date', 'subtotal', 'tax_amount', 'shipping', 'total', 'amount_paid',
        'balance_due', 'payment_terms', 'notes', 'currency'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->invoice_number)) {
                $model->invoice_number = 'INV-' . date('Y') . '-' . 
                    str_pad(DB::table('invoices')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'sent' => 'primary',
            'paid' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'danger'
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'sent')->where('due_date', '<', now());
    }
}