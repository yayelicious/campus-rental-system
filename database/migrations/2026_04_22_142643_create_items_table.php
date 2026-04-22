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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The owner of the item
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // Daily/weekly rental price
            $table->string('status')->default('available'); // available, rented, etc.
            $table->string('image_path')->nullable(); // For item photos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
