<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversaMedicinal extends Model
{
    use HasFactory;

    protected $table = 'medical_conversations';

    protected $fillable = [
        'diagnosis_id',
        'doctor_id',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function messages()
    {
        return $this->hasMany(ConversaMedicinalMensagem::class, 'medical_conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ConversaMedicinalMensagem::class, 'medical_conversation_id')->latestOfMany();
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
