<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->string('slogan')->nullable()->after('name');
            $t->string('factory_province', 100)->nullable();
            $t->string('factory_city', 100)->nullable();
            $t->text('factory_address')->nullable();
            $t->string('office_province', 100)->nullable();
            $t->string('office_city', 100)->nullable();
            $t->text('office_address')->nullable();
            $t->json('phones')->nullable();
            $t->json('contacts')->nullable();
            $t->json('tech_specs')->nullable();
            $t->json('activity_types')->nullable();
            $t->json('services')->nullable();
            $t->string('logo')->nullable();
            $t->string('cover')->nullable();
            $t->string('catalog')->nullable();
            $t->json('gallery')->nullable();
            $t->string('video_code')->nullable();
            $t->json('socials')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->dropColumn([
                'slogan',
                'factory_province',
                'factory_city',
                'factory_address',
                'office_province',
                'office_city',
                'office_address',
                'phones',
                'contacts',
                'tech_specs',
                'activity_types',
                'services',
                'logo',
                'cover',
                'catalog',
                'gallery',
                'video_code',
                'socials',
            ]);
        });
    }
};
