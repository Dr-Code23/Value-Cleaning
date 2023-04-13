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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->longText('message');
            $table->Text('seen_at')->default(false);
            $table->enum('type_message', ['text', 'image', 'audio']);
            $table->foreignId('sender_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('messages');
    }
};
