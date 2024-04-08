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
            $table->string('description')->nullable(); // Description column (nullable)
            // $table->binary('img'); // Image column (binary data)
            $table->string('product_type'); // Product type column
            $table->integer('price'); // Price column
            $table->tinyInteger('in_stock')->nullable(); // IN_Stock column (nullable)
            $table->string('categories'); // Categories column
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
