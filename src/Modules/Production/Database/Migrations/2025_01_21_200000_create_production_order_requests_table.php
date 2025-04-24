<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionOrderRequestsTable extends Migration
{
    
    public function up()
    {


        Schema::create('production_order_requests', function (Blueprint $table) {
            $table->id();

            $table->string('order_number')->unique();
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('cascade'); // Linked to the items table
            $table->decimal('quantity', 10, 2)->nullable(); // Planned production quantity

            $table->dateTime('due_date')->nullable();
            //$table->dateTime('start_date')->nullable();
            //$table->dateTime('end_date')->nullable();
            $table->foreignId('status_id')->constrained()->onDelete('cascade'); // Foreign key to the units table


            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->boolean('is_auto_generated')->default(true);

            // Accountability links
            $table->foreignId('supervisor_id')->nullable()->constrained('users');

            $table->boolean('is_verified')->default(false);  // To mark order closure verification
            $table->timestamp('verified_at')->nullable();  // Timestamp for approval
            $table->foreignId('verified_by')->nullable()->constrained('users');  // Approver info

            $table->boolean('is_approved')->default(false);  // To mark order closure verification
            $table->timestamp('approved_at')->nullable();  // Timestamp for approval
            $table->foreignId('approved_by')->nullable()->constrained('users');  // Approver info

            $table->text('remarks')->nullable();

            $table->timestamps();


        });






















        /*Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            //$table->string('name')->nullable();
            //$table->text('description')->nullable();

            $table->string('order_number')->unique();


            // 'order_time' can be removed if you use Laravel's 'created_at'
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade'); // User who created the order
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null'); // Supervisor/manager
            $table->string('batch_number')->unique(); // Unique batch number

            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null'); // Department responsible

            $table->dateTime('expected_start_time')->nullable(); // Start of production
            $table->dateTime('expected_end_time')->nullable(); // Expected end of production
            $table->dateTime('completed_at')->nullable(); // Actual end of production (optional)

            $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled'])->default('pending'); // Order status

            $table->decimal('planned_quantity', 10, 2)->nullable(); // Planned production quantity
            $table->decimal('actual_quantity', 10, 2)->nullable(); // Actual produced quantity (optional)

            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps(); // Adds 'created_at' and 'updated_at'





        });*/
    }



    public function down()
    {
        Schema::dropIfExists('production_order_requests');
    }
}
