<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'views_count')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->unsignedInteger('views_count')->default(0)->after('status');
            });
        }

        if (Schema::hasTable('job_applications') && !Schema::hasColumn('job_applications', 'views_count')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->unsignedInteger('views_count')->default(0)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'views_count')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }

        if (Schema::hasTable('job_applications') && Schema::hasColumn('job_applications', 'views_count')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }
    }
};
