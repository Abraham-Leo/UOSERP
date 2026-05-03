<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlEntry extends Model
{
    protected $fillable = [
        'gl_transaction_id', 'gl_account_id', 'debit', 'credit', 'description'
    ];

    protected $casts = [
        'debit' => 'decimal:4',
        'credit' => 'decimal:4'
    ];

    public function transaction()
    {
        return $this->belongsTo(GlTransaction::class);
    }

    public function account()
    {
        return $this->belongsTo(GlAccount::class);
    }
}