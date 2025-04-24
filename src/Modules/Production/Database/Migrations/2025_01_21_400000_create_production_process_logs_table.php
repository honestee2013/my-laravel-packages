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
        Schema::create('production_process_logs', function (Blueprint $table) {
            $table->id();
            $table->string('display_name')->nullable();

            $table->foreignId('production_batch_id')->nullable()->constrained('production_batches');
            $table->foreignId('production_process_id')->constrained();
            $table->foreignId('status_id')->nullable()->constrained()->onDelete('cascade'); // Foreign key to the units table
            $table->integer( 'progress')->default(0)->nullable();

            $table->foreignId('operator_id')->nullable()->constrained('users');
            $table->foreignId('supervisor_id')->nullable()->constrained('users');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('expected_end_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_process_logs');
    }
};
