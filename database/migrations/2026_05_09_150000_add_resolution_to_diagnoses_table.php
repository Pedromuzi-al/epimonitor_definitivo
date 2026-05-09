<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->boolean('is_resolved')->default(false)->after('alert_level');
            $table->timestamp('resolved_at')->nullable()->after('is_resolved');
            $table->string('resolution_reason')->nullable()->after('resolved_at');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->dropColumn(['is_resolved', 'resolved_at', 'resolution_reason']);
            $table->dropSoftDeletes();
        });
    }
};
