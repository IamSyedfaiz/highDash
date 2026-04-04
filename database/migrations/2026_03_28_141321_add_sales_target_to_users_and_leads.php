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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('sales_target_amount', 15, 2)->after('is_active')->default(0)->nullable();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('converted_amount', 15, 2)->after('feedback')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sales_target_amount');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('converted_amount');
        });
    }
};
