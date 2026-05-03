<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteLine extends Model
{
    protected $fillable = [
        'quote_id', 'part_id', 'line_number', 'quantity', 'unit_cost',
        'unit_price', 'discount_pct', 'line_total', 'notes'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}