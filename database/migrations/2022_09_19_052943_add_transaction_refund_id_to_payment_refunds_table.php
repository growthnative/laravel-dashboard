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
        Schema::table('payment_refunds', function (Blueprint $table) {
            $table->string('transaction_refund_id',100)->nullable()->after('id')->comment('This column is used to save refund id which we getting from response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_refunds', function (Blueprint $table) {
            $table->dropColumn('transaction_refund_id');
        });
    }
};
