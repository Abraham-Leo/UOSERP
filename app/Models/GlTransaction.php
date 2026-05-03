<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlTransaction extends Model
{
    protected $fillable = [
        'reference_number', 'transaction_type', 'transaction_date', 'description'
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function entries()
    {
        return $this->hasMany(GlEntry::class);
    }

    public function transactionable()
    {
        return $this->morphTo();
    }
}