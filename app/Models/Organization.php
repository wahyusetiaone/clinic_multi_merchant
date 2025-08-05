<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'path_logo',
        'owner_id',
    ];

    /**
     * Get the owner (User) that owns the Organization.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the branches for the Organization.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the employees for the Organization.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
