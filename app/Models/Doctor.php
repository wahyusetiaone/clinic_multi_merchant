<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors'; // Pastikan nama tabel benar

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'satusehat_id',
        'name',
        'specialization',
        'address',
        'phone_number',
        'str_number',
        'username',
        'start_date',
        'photo',
        'signature',
        'stamp',
        'branch_id',
        // 'user_id', // Uncomment jika Anda menambahkan user_id
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date', // Otomatis cast ke Carbon instance
    ];

    /**
     * Get the branch that the doctor belongs to.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // /**
    //  * Get the user account associated with the doctor.
    //  */
    // public function user()
    // {
    //     return $this->belongsTo(User::class); // Uncomment jika Anda menambahkan user_id
    // }
}
