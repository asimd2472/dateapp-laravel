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
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();

            // User who performed the action
            $table->unsignedBigInteger('from_user_id');

            // User who received the action
            $table->unsignedBigInteger('to_user_id');

            // like / dislike
            $table->enum('action', ['like', 'dislike']);

            $table->timestamps();

            // Prevent duplicate action for same user pair
            $table->unique(['from_user_id', 'to_user_id']);

            // Foreign keys
            $table->foreign('from_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('to_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_actions');
    }
};
