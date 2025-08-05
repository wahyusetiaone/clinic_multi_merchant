<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolyClinic extends Model
{
    use HasFactory;

    protected $table = 'polyclinics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'physical_location_type',
        'description',
        'branch_id', // <--- Tambahkan ini
    ];

    /**
     * Get the branch that owns the polyclinic.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
