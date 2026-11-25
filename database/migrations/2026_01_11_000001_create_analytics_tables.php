<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('daily_views')) {
            Schema::create('daily_views', function (Blueprint $table) {
                $table->id();
                $table->date('view_date')->index();
                $table->string('subject_type', 32); // 'site', 'company', 'category', 'province', 'job'
                $table->unsignedBigInteger('subject_id')->default(0);
                $table->unsignedBigInteger('total_views')->default(0);
                $table->unsignedBigInteger('unique_views')->default(0);
                $table->unsignedBigInteger('source_google')->default(0);
                $table->unsignedBigInteger('source_search')->default(0);
                $table->unsignedBigInteger('source_direct')->default(0);
                $table->unsignedBigInteger('source_internal')->default(0);
                $table->timestamps();

                $table->unique(['view_date', 'subject_type', 'subject_id'], 'daily_views_date_subject_unique');
                $table->index(['subject_type', 'subject_id', 'view_date'], 'daily_views_subject_date_idx');
            });
        }

        if (!Schema::hasTable('company_interactions')) {
            Schema::create('company_interactions', function (Blueprint $table) {
                $table->id();
                $table->date('stat_date')->index();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('phone_clicks')->default(0);
                $table->unsignedBigInteger('whatsapp_clicks')->default(0);
                $table->unsignedBigInteger('website_clicks')->default(0);
                $table->unsignedBigInteger('bookmark_clicks')->default(0);
                $table->timestamps();

                $table->unique(['stat_date', 'company_id'], 'company_interactions_date_company_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_interactions');
        Schema::dropIfExists('daily_views');
    }
};
