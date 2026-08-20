<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->string('status')->default('active')->after('role');
            $table->foreignId('invited_by_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('invited_at')->nullable()->after('invited_by_id');
        });
    }

    public function down(): void
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invited_by_id');
            $table->dropColumn(['status', 'invited_at']);
        });
    }
};
