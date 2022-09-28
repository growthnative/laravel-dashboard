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
        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payment_details')->onDelete('cascade')->nullable()->comment('This column is used make payment id as foreign key');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable()->comment('This column is used make user id as foreign key');
            $table->decimal('refund_amount')->nullable()->comment('This column is used to save data(i.e how much amount is requested to get refunded)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_refunds');
    }
};
