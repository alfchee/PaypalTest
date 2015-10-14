<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('orders')) {
            Schema::create('orders', function($table) {
                $table->increments('id');
                $table->string('user_name',254)->nullable();
                $table->string('payment_id',50)->nullable();
                $table->string('state',20)->nullable();
                $table->float('amount')->nullable();
                $table->string('description',80)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
