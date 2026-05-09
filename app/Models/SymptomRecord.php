<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'diagnosis_id',
        'symptoms',
    ];

    protected $casts = [
        'symptoms' => 'array',
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
