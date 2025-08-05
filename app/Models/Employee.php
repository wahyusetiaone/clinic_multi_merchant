<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'branch_id',
        'user_id',
        'name',
        'nip',
        'position',
        'type',
        'phone',
        'address',
        'path_photo',
    ];

    /**
     * Get the organization that the employee belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the branch that the employee works at (if any).
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the position that the employee works at (if any).
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the user account associated with the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
