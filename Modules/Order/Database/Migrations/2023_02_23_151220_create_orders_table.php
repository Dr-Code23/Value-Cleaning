<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->double('worke_aera');
            $table->date('date');
            $table->time('time');
            $table->string('address');
            $table->enum('repeat', ['once', 'weekly', 'monthly']);
            $table->enum('status', ['Processing', 'Cansaled', 'Finished'])->default('Processing');
            $table->enum('payment_status', ['Credit', 'Receipt'])->default('Receipt');;
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->float('delivery_price')->nullable();
            $table->double('total_price');
            $table->string('order_code');
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
        Schema::dropIfExists('Order');
    }
};
