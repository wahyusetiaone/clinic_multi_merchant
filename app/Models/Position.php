<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'valid_branch_types',
    ];

    protected $casts = [
        'valid_branch_types' => 'array'
    ];

    /**
     * Get the employees that have this position.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
