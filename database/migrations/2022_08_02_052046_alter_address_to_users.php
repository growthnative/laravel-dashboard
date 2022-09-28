<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address',100)->nullable()->change();
            $table->string('state',20)->nullable()->change();
            $table->string('city',20)->nullable()->change();
            $table->string('zip_code',10)->nullable()->change();
            $table->string('profile_image',100)->nullable()->change();
            $table->string('social_id',50)->nullable()->change();
            $table->string('social_type',15)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('zip_code');
            $table->dropColumn('profile_image');
            $table->dropColumn('social_id');
            $table->dropColumn('social_type');
        });
    }
};
