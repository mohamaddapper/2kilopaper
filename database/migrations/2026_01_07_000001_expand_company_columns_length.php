<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE companies MODIFY phone VARCHAR(255) NULL");
            DB::statement("ALTER TABLE companies MODIFY address TEXT NULL");
        } catch (\Throwable $e) {
            Schema::table('companies', function (Blueprint $t) {
                $t->string('phone', 255)->nullable()->change();
                $t->text('address')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->string('phone', 20)->nullable()->change();
            $t->string('address', 255)->nullable()->change();
        });
    }
};
