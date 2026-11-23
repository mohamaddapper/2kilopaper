<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $t) {
            if (!Schema::hasColumn('categories', 'term_id')) {
                $t->unsignedBigInteger('term_id')->nullable()->after('slug')->index();
            }
            if (!Schema::hasColumn('categories', 'icon')) {
                $t->string('icon', 50)->nullable()->after('term_id');
            }
            if (!Schema::hasColumn('categories', 'description')) {
                $t->text('description')->nullable()->after('icon');
            }
        });

        if (!Schema::hasTable('company_categories')) {
            Schema::create('company_categories', function (Blueprint $t) {
                $t->id();
                $t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $t->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $t->boolean('is_primary')->default(false);
                $t->unique(['company_id', 'category_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_categories');

        Schema::table('categories', function (Blueprint $t) {
            if (Schema::hasColumn('categories', 'term_id')) {
                $t->dropColumn(['term_id', 'icon', 'description']);
            }
        });
    }
};
