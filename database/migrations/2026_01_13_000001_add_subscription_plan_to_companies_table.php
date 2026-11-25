<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                if (!Schema::hasColumn('companies', 'subscription_plan')) {
                    $table->string('subscription_plan', 16)->nullable()->after('tier_expires_at');
                }
                if (!Schema::hasColumn('companies', 'subscription_expires_at')) {
                    $table->timestamp('subscription_expires_at')->nullable()->after('subscription_plan');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                foreach (['subscription_plan', 'subscription_expires_at'] as $col) {
                    if (Schema::hasColumn('companies', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
