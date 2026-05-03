<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Rma extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rma_number', 'customer_id', 'order_id', 'type', 'status',
        'rma_date', 'handling_charges', 'credit_amount', 'reason', 'notes'
    ];

    protected $casts = [
        'rma_date' => 'date',
        'handling_charges' => 'decimal:2',
        'credit_amount' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->rma_number)) {
                $model->rma_number = 'RMA-' . date('Y') . '-' . 
                    str_pad(DB::table('rmas')->count() + 1, 4, '0', STR_PAD_LEFT);
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
}