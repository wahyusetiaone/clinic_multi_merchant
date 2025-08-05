<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the drug stocks for the unit.
     */
    public function drugStocks()
    {
        return $this->hasMany(DrugStock::class);
    }
}
