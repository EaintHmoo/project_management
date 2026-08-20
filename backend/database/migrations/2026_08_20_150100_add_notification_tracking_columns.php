<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->timestamp('overdue_notified_at')->nullable()->after('due_at');
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('overdue_notified_at');
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
        });
    }
};
