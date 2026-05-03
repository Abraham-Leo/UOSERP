<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'quote_id', 'sales_rep_id', 'type',
        'status', 'order_date', 'due_date', 'ship_date', 'work_start_date',
        'customer_po', 'payment_terms', 'ship_via', 'shipping_account',
        'ship_to_name', 'ship_to_address1', 'ship_to_city', 'ship_to_state',
        'ship_to_zip', 'ship_to_country', 'shipping_cost', 'tax_rate',
        'discount_pct', 'subtotal', 'tax_amount', 'total', 'paid', 'notes',
        'internal_notes', 'currency', 'warehouse_id', 'released'
    ];

    protected $casts = [
        'order_date' => 'date',
        'due_date' => 'date',
        'ship_date' => 'date',
        'work_start_date' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'released' => 'boolean',
        'released_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = 'SO-' . date('Y') . '-' . 
                    str_pad(DB::table('orders')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function lines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'new' => 'primary',
            'in_progress' => 'warning',
            'shipped' => 'info',
            'invoiced' => 'success',
            'cancelled' => 'secondary'
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'invoiced']);
    }

    public function getBalanceDueAttribute()
    {
        return $this->total - $this->paid;
    }
}