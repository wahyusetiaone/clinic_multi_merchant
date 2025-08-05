<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the drugs for the drug group.
     */
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }
}
