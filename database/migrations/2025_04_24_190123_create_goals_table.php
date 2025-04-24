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
        Schema::create('goals', function (Blueprint $table) {
            $table->id('goal_id');
            $table->string('goal_name', 100);
            $table->decimal('target_amount', 15, 2);
            $table->date('deadline_date')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories', 'category_id')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
