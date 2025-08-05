<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'type',
        'address',
        'phone',
        'path_logo',
    ];

    /**
     * Get the organization that owns the branch.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the employees that work at the branch.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
