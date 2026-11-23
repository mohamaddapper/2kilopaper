<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('provinces')) {
            Schema::create('provinces', function (Blueprint $t) {
                $t->id();
                $t->string('name', 100);
                $t->string('slug', 100)->unique();
                $t->unsignedBigInteger('term_id')->nullable()->index();
                $t->text('description')->nullable();
                $t->unsignedSmallInteger('sort')->default(0);
                $t->timestamps();
            });
        }

        Schema::table('cities', function (Blueprint $t) {
            if (!Schema::hasColumn('cities', 'province_id')) {
                $t->foreignId('province_id')->nullable()->after('id')->constrained('provinces')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('cities', 'term_id')) {
                $t->unsignedBigInteger('term_id')->nullable()->after('slug')->index();
            }
            if (!Schema::hasColumn('cities', 'description')) {
                $t->text('description')->nullable()->after('term_id');
            }
        });

        Schema::table('companies', function (Blueprint $t) {
            if (!Schema::hasColumn('companies', 'province_id')) {
                $t->foreignId('province_id')->nullable()->after('category_id')->constrained('provinces')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            if (Schema::hasColumn('companies', 'province_id')) {
                $t->dropForeign(['province_id']);
                $t->dropColumn('province_id');
            }
        });

        Schema::table('cities', function (Blueprint $t) {
            if (Schema::hasColumn('cities', 'province_id')) {
                $t->dropForeign(['province_id']);
                $t->dropColumn(['province_id', 'term_id', 'description']);
            }
        });

        Schema::dropIfExists('provinces');
    }
};
