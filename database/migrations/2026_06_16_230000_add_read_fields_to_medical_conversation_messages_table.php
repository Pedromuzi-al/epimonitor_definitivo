<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadFieldsToMedicalConversationMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('medical_conversation_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_conversation_messages', 'read')) {
                $table->boolean('read')->default(false)->after('message');
            }

            if (!Schema::hasColumn('medical_conversation_messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('read');
            }
        });
    }

    public function down()
    {
        Schema::table('medical_conversation_messages', function (Blueprint $table) {
            if (Schema::hasColumn('medical_conversation_messages', 'read_at')) {
                $table->dropColumn('read_at');
            }

            if (Schema::hasColumn('medical_conversation_messages', 'read')) {
                $table->dropColumn('read');
            }
        });
    }
}
