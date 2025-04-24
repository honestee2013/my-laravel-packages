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
        Schema::create('production_process_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_process_log_id')->constrained('production_process_logs')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->decimal('actual_quantity', 10, 2)->nullable()->default(0.00); // Quantity of the resource/item
            $table->decimal('planned_quantity', 10, 2)->nullable()->default(0.00); // Quantity of the resource/item
            $table->timestamps();
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_process_outputs');
    }
};
