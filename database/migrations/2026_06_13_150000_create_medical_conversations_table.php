<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('medical_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->unique()->constrained('diagnoses')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('open');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_conversations');
    }
}
