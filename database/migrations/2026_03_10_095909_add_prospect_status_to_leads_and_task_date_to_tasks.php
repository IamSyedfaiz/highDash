<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('prospect_status', ['Approach', 'Negotiable', 'Order Won', 'Order Lost', 'None'])->default('None')->after('status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->date('task_date')->nullable()->after('url');
        });

        // Populate existing tasks with their created_at date
        DB::table('tasks')->update(['task_date' => DB::raw('DATE(created_at)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('prospect_status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('task_date');
        });
    }
};
