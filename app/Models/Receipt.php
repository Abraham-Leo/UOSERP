<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_number', 'purchase_order_id', 'vendor_id', 'receipt_date',
        'packing_slip', 'notes', 'received_by', 'status'
    ];

    protected $casts = [
        'receipt_date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->receipt_number)) {
                $model->receipt_number = 'REC-' . date('Y') . '-' . 
                    str_pad(DB::table('receipts')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function lines()
    {
        return $this->hasMany(ReceiptLine::class);
    }
}