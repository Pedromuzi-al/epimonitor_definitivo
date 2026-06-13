<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'disease_id',
        'probability',
        'symptoms',
        'neighborhood',
        'alert_level',
        'is_resolved',
        'resolved_at',
        'resolution_reason',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
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

    public function conversation()
    {
        return $this->hasOne(ConversaMedicinal::class);
    }

    /**
     * Marcar diagnóstico como resolvido
     */
    public function markAsResolved(string $reason = null): void
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolution_reason' => $reason,
        ]);
    }

    /**
     * Verificar se está resolvido
     */
    public function isResolved(): bool
    {
        return $this->is_resolved === true;
    }

    /**
     * Obter apenas diagnósticos não resolvidos
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Obter apenas diagnósticos resolvidos
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }
}
