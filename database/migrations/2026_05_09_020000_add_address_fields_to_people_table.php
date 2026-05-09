<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressFieldsToPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('zip_code', 8)->nullable()->after('neighborhood');
            $table->string('street')->nullable()->after('zip_code');
            $table->string('house_number', 20)->nullable()->after('street');
            $table->string('housing_type', 20)->nullable()->after('house_number');
            $table->string('address_complement')->nullable()->after('housing_type');
            $table->string('city', 100)->nullable()->after('address_complement');
            $table->string('state', 2)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn([
                'zip_code',
                'street',
                'house_number',
                'housing_type',
                'address_complement',
                'city',
                'state',
            ]);
        });
    }
}
