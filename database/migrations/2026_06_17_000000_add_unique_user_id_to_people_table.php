<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueUserIdToPeopleTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('people', 'user_id')) {
            return;
        }

        $duplicates = DB::table('people')
            ->select('user_id', DB::raw('MIN(id) as keep_id'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('people')
                ->where('user_id', $duplicate->user_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->update(['user_id' => null]);
        }

        Schema::table('people', function (Blueprint $table) {
            $table->unique('user_id', 'people_user_id_unique');
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('people', 'user_id')) {
            return;
        }

        Schema::table('people', function (Blueprint $table) {
            $table->dropUnique('people_user_id_unique');
        });
    }
}
