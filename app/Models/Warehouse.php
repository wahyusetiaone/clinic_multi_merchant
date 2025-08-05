<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'branch_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the branch that owns the warehouse.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the locations for the warehouse.
     */
    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Get the drug stocks for the warehouse.
     */
    public function drugStocks()
    {
        return $this->hasMany(DrugStock::class);
    }
}
