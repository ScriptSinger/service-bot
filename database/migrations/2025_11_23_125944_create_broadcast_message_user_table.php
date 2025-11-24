<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('broadcast_message_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_message_id')
                ->constrained('broadcast_messages')
                ->cascadeOnDelete();

            // Внешний ключ на telegram_users
            $table->foreignId('telegram_user_id')
                ->constrained('telegram_users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_message_user');
    }
};
