<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'disease_id',
        'probability',
        'symptoms',
        'neighborhood',
        'alert_level',
    ];

    protected $casts = [
        'symptoms' => 'array',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    public function symptomRecords()
    {
        return $this->hasMany(SymptomRecord::class);
    }
}
