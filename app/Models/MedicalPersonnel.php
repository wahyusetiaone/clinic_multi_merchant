<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalPersonnel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medical_personnel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'nik',
        'satusehat_id',
        'name',
        'specialization',
        'address',
        'phone_number',
        'email',
        'start_date',
        'branch_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
    ];

    /**
     * Get the branch that owns the medical personnel.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
