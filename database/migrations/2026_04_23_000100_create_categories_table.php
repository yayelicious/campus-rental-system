<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->default('spark');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('categories')->insert([
            ['name' => 'Books', 'slug' => 'books', 'icon' => 'book', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gadgets', 'slug' => 'gadgets', 'icon' => 'device', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electronics', 'slug' => 'electronics', 'icon' => 'speaker', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tools', 'slug' => 'tools', 'icon' => 'tool', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports & PE Essentials', 'slug' => 'sports-pe', 'icon' => 'fitness', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => 'spark', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
