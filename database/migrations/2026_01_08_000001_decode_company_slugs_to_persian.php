<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        $companies = Company::all();
        $usedSlugs = [];

        foreach ($companies as $company) {
            $slug = $company->slug;
            if (str_contains($slug, '%')) {
                // Decode urlencoded slug (e.g. %d8%b4... -> شرکت...)
                while (str_contains($slug, '%')) {
                    $decoded = urldecode($slug);
                    if ($decoded === $slug) {
                        break;
                    }
                    $slug = $decoded;
                }
            }

            $slug = trim($slug);
            if (!$slug) {
                $slug = preg_replace('/\s+/u', '-', trim($company->name));
            }
            $slug = mb_substr($slug, 0, 180);

            // Ensure uniqueness
            $baseSlug = $slug;
            $counter = 1;
            while (isset($usedSlugs[$slug]) || Company::where('slug', $slug)->where('id', '!=', $company->id)->exists()) {
                $slug = mb_substr($baseSlug, 0, 170) . '-' . (++$counter);
            }
            $usedSlugs[$slug] = true;

            if ($company->slug !== $slug) {
                $company->slug = $slug;
                $company->save();
            }
        }
    }

    public function down(): void
    {
        // No need to revert clean slugs to encoded ones
    }
};
