<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalConversationMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('medical_conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_conversation_id')->constrained('medical_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_type');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_conversation_messages');
    }
}
