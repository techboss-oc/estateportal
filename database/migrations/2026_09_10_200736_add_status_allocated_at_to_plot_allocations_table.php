<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plot_allocations', function (Blueprint $table) {
            $table->string('status')->default('active')->after('allocation_reference'); // active, cancelled, suspended
            $table->timestamp('allocated_at')->nullable()->after('allocation_date');
            $table->index(['plot_id', 'status'], 'idx_plot_active_status');
        });
    }

    public function down(): void
    {
        Schema::table('plot_allocations', function (Blueprint $table) {
            $table->dropIndex('idx_plot_active_status');
            $table->dropColumn(['status', 'allocated_at']);
        });
    }
};
