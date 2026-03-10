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
        // Data Preparation: Normalize calling_status in leads
        DB::table('leads')->where('calling_status', 'Busy')->update(['calling_status' => 'Busy / Callback']);

        // Data Preparation: Normalize status in lead_follow_ups
        DB::table('lead_follow_ups')->where('status', 'Busy')->update(['status' => 'Busy / Callback']);

        // 1. Leads Table
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'New Lead', 'Existing', 'Drop'])->default('Pending')->change();
            $table->enum('business_type', ['Manufacturer', 'Supplier', 'Trader', 'Wholesaler', 'Importer', 'Exporter', 'Service Provider'])->change();
            $table->enum('calling_status', ['Call Answered', 'Busy / Callback', 'Not Answered', 'Interested', 'Not Interested', 'Switched Off', 'Wrong Number'])->nullable()->change();
        });

        // 2. Lead Follow Ups
        Schema::table('lead_follow_ups', function (Blueprint $table) {
            $table->enum('status', ['Call Answered', 'Busy / Callback', 'Not Answered', 'Interested', 'Not Interested', 'Switched Off', 'Wrong Number'])->change();
        });

        // 3. Tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['pending', 'started', 'closed'])->default('pending')->change();
        });

        // 4. Leave Requests
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
            $table->enum('type', ['casual', 'sick', 'annual', 'unpaid', 'other'])->change();
        });

        // 5. Attendance
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', ['present', 'half-day', 'late', 'absent', 'on_leave'])->default('present')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
            $table->string('business_type')->change();
            $table->string('calling_status')->nullable()->change();
        });

        Schema::table('lead_follow_ups', function (Blueprint $table) {
            $table->string('status')->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->string('type')->change();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('status')->default('present')->change();
        });
    }
};
