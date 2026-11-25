<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'admin_role')) {
                    $table->string('admin_role', 32)->nullable()->after('is_admin');
                }
                if (!Schema::hasColumn('users', 'admin_permissions')) {
                    $table->text('admin_permissions')->nullable()->after('admin_role');
                }
                if (!Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('admin_permissions');
                }
            });
        }

        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                if (!Schema::hasColumn('companies', 'admin_note')) {
                    $table->text('admin_note')->nullable();
                }
            });
        }

        if (!Schema::hasTable('admin_logs')) {
            Schema::create('admin_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('action', 64)->index();
                $table->string('target_type', 40)->nullable();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->string('description', 500);
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('created_at')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'admin_note')) {
            Schema::table('companies', fn (Blueprint $table) => $table->dropColumn('admin_note'));
        }
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                foreach (['admin_role', 'admin_permissions', 'last_login_at'] as $col) {
                    if (Schema::hasColumn('users', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
