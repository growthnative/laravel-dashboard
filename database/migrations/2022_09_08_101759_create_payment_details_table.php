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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id',100)->nullable()->comment('This column is used to save transaction id which we getting from response');
            $table->string('card_name',20)->nullable();
            $table->string('card_number',10)->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('This column is used make user id as foreign key');
            $table->decimal('amount')->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('exp_month',20)->nullable();
            $table->string('exp_year',20)->nullable();
            $table->enum('status', array(1,2,3))->default(1)->comment('1 =>processing,2=>succeeded,3=>canceled');
            $table->string('payment_method' ,20)->nullable();
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
        Schema::dropIfExists('payment_details');
    }
};
