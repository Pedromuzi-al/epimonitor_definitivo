<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            if (!Schema::hasColumn('people', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('state')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            if (Schema::hasColumn('people', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
}
