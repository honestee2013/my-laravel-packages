<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    public function up()
    {
        Schema::create('production_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Linked to the items table
            $table->decimal('quantity', 10, 2)->nullable()->default(0.00); // Quantity of the resource/item
            $table->text('note')->nullable();
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('production_order_items');
    }
};

