<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientNotesToDiagnosesTable extends Migration
{
    public function up()
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            if (!Schema::hasColumn('diagnoses', 'patient_notes')) {
                $table->text('patient_notes')->nullable()->after('symptoms');
            }
        });
    }

    public function down()
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            if (Schema::hasColumn('diagnoses', 'patient_notes')) {
                $table->dropColumn('patient_notes');
            }
        });
    }
}
