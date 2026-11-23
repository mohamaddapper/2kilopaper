<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            if (!Schema::hasColumn('companies', 'email')) {
                $t->string('email', 100)->nullable()->after('website');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            if (Schema::hasColumn('companies', 'email')) {
                $t->dropColumn('email');
            }
        });
    }
};
