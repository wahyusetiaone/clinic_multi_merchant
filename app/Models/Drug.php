<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'manufacturer_id',
        'group_id',
        'category_id',
        'drug_type',
        'min_stock',
        'description',
        'indication',
        'content',
        'dosage',
        'packaging',
        'side_effects',
        'precursor_active_ingredient',
        'branch_id',
        'label_id',
    ];

    protected $casts = [
        // 'min_stock' => 'integer', // Sudah di migrasi integer, tidak selalu perlu cast eksplisit
    ];

    /**
     * Get the manufacturer that produces the drug.
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    /**
     * Get the drug group for the drug.
     */
    public function drugGroup()
    {
        return $this->belongsTo(DrugGroup::class, 'group_id'); // Custom foreign key for clarity
    }

    /**
     * Get the drug category for the drug.
     */
    public function drugCategory()
    {
        return $this->belongsTo(DrugCategory::class, 'category_id'); // Custom foreign key for clarity
    }

    /**
     * Get the branch that the drug belongs to.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the label (aturan pakai/etiket) for the drug.
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    /**
     * Get the drug details associated with the drug.
     */
    public function drugDetail()
    {
        return $this->hasOne(DrugDetail::class);
    }

    /**
     * Get the drug stocks for the drug.
     */
    public function drugStocks()
    {
        return $this->hasMany(DrugStock::class);
    }
}
