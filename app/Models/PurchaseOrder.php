<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po_number', 'vendor_id', 'buyer_id', 'type', 'status', 'po_date',
        'requested_date', 'acknowledged_date', 'vendor_po_number', 'fob',
        'ship_via', 'warehouse_id', 'payment_terms', 'subtotal', 'tax_amount',
        'total', 'amount_billed', 'amount_paid', 'notes', 'currency',
        'work_order_id', 'acknowledged'
    ];

    protected $casts = [
        'po_date' => 'date',
        'requested_date' => 'date',
        'acknowledged_date' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_billed' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'acknowledged' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->po_number)) {
                $model->po_number = 'PO-' . date('Y') . '-' . 
                    str_pad(DB::table('purchase_orders')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function lines()
    {
        return $this->hasMany(PoLine::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getBalanceAttribute()
    {
        return $this->total - $this->amount_paid;
    }
}