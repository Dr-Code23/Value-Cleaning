<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_area');
            $table->date('date');
            $table->time('time');
            $table->string('address');
            $table->double('latitude', 15, 8)->nullable();
            $table->double('longitude', 15, 8)->nullable();
            $table->integer('day');
            $table->enum('repeat', ['once', 'weekly', 'monthly']);
            $table->enum('status', ['processing', 'canceled', 'finished'])->default('processing');
            $table->enum('payment_status', ['credit', 'receipt'])->default('receipt');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->dateTime('scheduled_at')->nullable();
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
