<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rxcui',
        'drug_name',
        'base_names',
        'dosage_forms',
        'user_id',
    ];

    protected $casts = [
        'base_names' => 'array',
        'dosage_forms' => 'array',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
