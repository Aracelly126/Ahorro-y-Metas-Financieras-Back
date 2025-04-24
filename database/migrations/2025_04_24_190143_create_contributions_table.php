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
        Schema::create('contributions', function (Blueprint $table) {
            $table->id('contribution_id');
            $table->decimal('amount', 15, 2);
            $table->dateTime('contribution_date')->useCurrent();
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->foreignId('goal_id')->constrained('goals', 'goal_id')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
