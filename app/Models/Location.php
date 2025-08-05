<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'name',
    ];

    /**
     * Get the warehouse that owns the location.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the drug stocks for the location.
     */
    public function drugStocks()
    {
        return $this->hasMany(DrugStock::class);
    }
}
