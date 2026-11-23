<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('job_type')->default('full_time'); // full_time, part_time, shift, project, internship
            $table->string('work_shift')->nullable(); // day, rotational, night, shift
            $table->string('gender')->default('any'); // any, male, female
            $table->string('military_service')->nullable(); // exempt_or_done, not_required
            $table->string('experience_years')->default('0'); // 0, 1-3, 3-6, 6+
            $table->string('salary_type')->default('negotiable'); // negotiable, ministry_plus, range
            $table->unsignedBigInteger('salary_min')->nullable();
            $table->unsignedBigInteger('salary_max')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('workplace_type')->default('factory'); // factory, office, store
            $table->text('address')->nullable();
            $table->json('benefits')->nullable(); // has_service, service_routes, has_meal, has_accommodation, has_insurance, has_supplementary_insurance, has_bonus
            $table->json('skills')->nullable(); // specialized machine names and technical skills
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('contact_type')->default('platform'); // platform, direct, both
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->string('status')->default('active'); // active, pending, expired, closed
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_immediate')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('birth_year')->nullable();
            $table->string('gender')->nullable();
            $table->string('military_status')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('education')->nullable();
            $table->string('experience_years')->nullable();
            $table->string('last_company')->nullable();
            $table->json('skills')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('resume_file')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('status')->default('new'); // new, reviewed, interview, rejected, hired
            $table->text('employer_note')->nullable();
            $table->timestamp('interview_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_postings');
    }
};
