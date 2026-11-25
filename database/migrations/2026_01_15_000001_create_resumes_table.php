<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('resumes')) {
            Schema::create('resumes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('full_name', 150);
                $table->string('phone', 30);
                $table->string('job_title', 190);
                $table->string('specialty', 100)->nullable()->index();
                $table->text('machines')->nullable();
                $table->unsignedBigInteger('province_id')->nullable()->index();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->unsignedTinyInteger('experience_years')->default(0);
                $table->string('work_type', 40)->default('full_time');
                $table->boolean('needs_accommodation')->default(false);
                $table->string('expected_salary', 100)->nullable();
                $table->text('bio')->nullable();
                $table->string('resume_file', 255)->nullable();
                $table->string('availability', 30)->default('available')->index();
                $table->string('status', 20)->default('pending')->index();
                $table->unsignedBigInteger('views_count')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
