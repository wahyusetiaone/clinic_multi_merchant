<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_id',
        'hna',
        'selling_price_1',
        'discount_1',
        'selling_price_2',
        'discount_2',
        'selling_price_3',
        'discount_3',
        'barcode',
    ];

    protected $casts = [
        'hna' => 'decimal:2',
        'selling_price_1' => 'decimal:2',
        'discount_1' => 'decimal:2',
        'selling_price_2' => 'decimal:2',
        'discount_2' => 'decimal:2',
        'selling_price_3' => 'decimal:2',
        'discount_3' => 'decimal:2',
    ];

    /**
     * Get the drug that owns the drug detail.
     */
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
