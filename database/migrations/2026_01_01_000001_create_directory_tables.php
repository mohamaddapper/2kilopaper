<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $t) {
            $t->id();
            $t->string('phone', 11)->index();
            $t->string('code_hash');
            $t->unsignedTinyInteger('attempts')->default(0);
            $t->timestamp('expires_at');
            $t->timestamp('used_at')->nullable();
            $t->timestamps();
        });
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedSmallInteger('sort')->default(0);
        });
        Schema::create('cities', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
        });
        Schema::create('companies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('category_id')->constrained();
            $t->foreignId('city_id')->constrained();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->string('phone', 20)->nullable();
            $t->string('website')->nullable();
            $t->string('address')->nullable();
            $t->string('status', 10)->default('pending')->index(); // pending|approved|rejected
            $t->string('tier', 10)->nullable();                    // gold|silver|bronze
            $t->timestamp('tier_expires_at')->nullable();
            $t->timestamps();
            $t->index(['status', 'category_id', 'city_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('otps');
    }
};
