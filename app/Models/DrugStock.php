<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_id',
        'warehouse_id',
        'location_id',
        'stock_quantity',
        'unit_id',
        'batch_number',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    /**
     * Get the drug that owns the drug stock.
     */
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    /**
     * Get the warehouse that the drug stock is in.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the location that the drug stock is in.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the unit of the drug stock.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
