<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApVoucher extends Model
{
    protected $fillable = [
        'voucher_number', 'vendor_id', 'purchase_order_id', 'vendor_invoice_number',
        'status', 'invoice_date', 'due_date', 'amount', 'amount_paid',
        'balance', 'notes', 'gl_account'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}