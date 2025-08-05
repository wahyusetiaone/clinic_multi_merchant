<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the drugs for the drug category.
     */
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }
}
