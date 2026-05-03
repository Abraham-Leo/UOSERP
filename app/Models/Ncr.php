<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Ncr extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ncr_number', 'title', 'description', 'status', 'source', 'disposition',
        'part_id', 'order_id', 'receipt_id', 'vendor_id', 'quantity', 'cost_impact',
        'containment_area', 'assigned_to', 'created_by', 'due_date', 'closed_at', 'resolution'
    ];

    protected $casts = [
        'due_date' => 'date',
        'closed_at' => 'datetime',
        'quantity' => 'decimal:4',
        'cost_impact' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->ncr_number)) {
                $model->ncr_number = 'NCR-' . date('Y') . '-' . 
                    str_pad(DB::table('ncrs')->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}