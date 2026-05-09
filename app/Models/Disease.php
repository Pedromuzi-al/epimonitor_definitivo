<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'symptom_weights',
    ];

    protected $casts = [
        'symptom_weights' => 'array',
    ];

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
