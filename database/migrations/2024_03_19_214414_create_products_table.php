<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id'); // Auto-incrementing primary key
            $table->string('name'); // Name column
            $table->string('description')->nullable(); // Description column 
            $table->string('img'); // File path for the image
            $table->string('product_type'); // Product type column
            $table->decimal('price', 8, 2); // Price column
            $table->integer('in_stock'); // IN_Stock column
            $table->string('category'); // Category column
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
