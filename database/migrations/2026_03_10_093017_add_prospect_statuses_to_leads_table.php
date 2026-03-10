<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('status', [
                'Pending',
                'New Lead',
                'Existing',
                'Drop',
                'Prospect',
                'Approach',
                'Negotiable',
                'Order won'
            ])->default('Pending')->change();

            $table->index('status');
            $table->index('assigned_to');
            $table->index('city');
            $table->index('business_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['assigned_to']);
            $table->dropIndex(['city']);
            $table->dropIndex(['business_type']);

            $table->enum('status', ['Pending', 'New Lead', 'Existing', 'Drop'])->default('Pending')->change();
        });
    }
};
