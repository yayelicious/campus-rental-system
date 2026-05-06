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
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('admin_removed_by')->nullable()->after('deleted_at')->constrained('users')->nullOnDelete();
            $table->string('admin_removal_reason')->nullable()->after('admin_removed_by');
            $table->text('admin_removal_details')->nullable()->after('admin_removal_reason');
            $table->timestamp('admin_removed_at')->nullable()->after('admin_removal_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_removed_by');
            $table->dropColumn([
                'admin_removal_reason',
                'admin_removal_details',
                'admin_removed_at',
            ]);
        });
    }
};
