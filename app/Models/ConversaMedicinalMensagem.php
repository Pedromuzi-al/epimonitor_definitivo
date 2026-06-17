<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversaMedicinalMensagem extends Model
{
    use HasFactory;

    protected $table = 'medical_conversation_messages';

    protected $fillable = [
        'medical_conversation_id',
        'user_id',
        'sender_type',
        'message',
        'read',
        'read_at',
    ];

    protected $casts = [
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(ConversaMedicinal::class, 'medical_conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
