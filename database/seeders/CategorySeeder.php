<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/categories.csv');
        if (!File::exists($csvPath)) {
            $this->command?->error("Categories CSV file not found at: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->command?->error("Unable to open CSV file: {$csvPath}");
            return;
        }

        $header = fgetcsv($handle, null, ',', '"', '\\');
        $rawRows = [];
        while (($row = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
            if (count($row) === count($header)) {
                $rawRows[] = array_combine($header, $row);
            }
        }
        fclose($handle);

        $this->command?->info('Processing ' . count($rawRows) . ' categories from CSV...');

        // 1. First Pass: Create or update all categories
        // We promote 'printing-services' to be a top-level parent (Pillar 2: چاپخانه‌ها)
        $categoryMap = []; // slug => Category model

        // Define top-level pillars with icons
        $pillarsConfig = [
            'factories' => ['name' => 'تولیدکنندگان', 'icon' => '🏭', 'sort' => 1],
            'printing-services' => ['name' => 'چاپخانه‌ها و مجتمع‌های چاپ', 'icon' => '🖨️', 'sort' => 2],
            'stores' => ['name' => 'فروشندگان و تأمین‌کنندگان', 'icon' => '🏪', 'sort' => 3],
            'importers' => ['name' => 'واردکنندگان', 'icon' => '🚢', 'sort' => 4],
            'service-providers' => ['name' => 'خدمات‌دهندگان فنی و تکمیلی', 'icon' => '⚙️', 'sort' => 5],
        ];

        // First insert top-level pillars
        foreach ($pillarsConfig as $slug => $cfg) {
            // Find in rawRows to get term_id
            $matched = null;
            foreach ($rawRows as $r) {
                if ($r['term_slug'] === $slug) {
                    $matched = $r;
                    break;
                }
            }

            $termId = $matched ? (int)$matched['term_id'] : null;
            $desc = $matched ? trim($matched['term_content']) : null;

            $cat = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => null,
                    'name' => $cfg['name'],
                    'term_id' => $termId,
                    'icon' => $cfg['icon'],
                    'description' => $desc,
                    'sort' => $cfg['sort'],
                ]
            );
            $categoryMap[$slug] = $cat;
        }

        // Second: Create all other categories in multiple passes to resolve parent_id
        $remaining = [];
        foreach ($rawRows as $r) {
            $slug = trim($r['term_slug']);
            if (isset($pillarsConfig[$slug])) {
                continue; // Already handled as top-level pillar
            }
            $remaining[] = $r;
        }

        // Iteratively resolve parents
        $maxLoops = 5;
        $sortIdx = 10;
        while (!empty($remaining) && $maxLoops-- > 0) {
            $stillRemaining = [];
            foreach ($remaining as $r) {
                $slug = trim($r['term_slug']);
                $parentSlug = trim($r['term_parent']);

                // If parent is printing-services, parent is already in categoryMap
                if (isset($categoryMap[$parentSlug])) {
                    $parentId = $categoryMap[$parentSlug]->id;
                    $cat = Category::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'parent_id' => $parentId,
                            'name' => trim($r['term_title']),
                            'term_id' => !empty($r['term_id']) ? (int)$r['term_id'] : null,
                            'description' => !empty($r['term_content']) ? trim($r['term_content']) : null,
                            'sort' => $sortIdx++,
                        ]
                    );
                    $categoryMap[$slug] = $cat;
                } else {
                    $stillRemaining[] = $r;
                }
            }
            if (count($stillRemaining) === count($remaining)) {
                // If parents cannot be resolved, attach remaining directly to service-providers
                foreach ($stillRemaining as $r) {
                    $slug = trim($r['term_slug']);
                    $cat = Category::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'parent_id' => $categoryMap['service-providers']->id,
                            'name' => trim($r['term_title']),
                            'term_id' => !empty($r['term_id']) ? (int)$r['term_id'] : null,
                            'description' => !empty($r['term_content']) ? trim($r['term_content']) : null,
                            'sort' => $sortIdx++,
                        ]
                    );
                    $categoryMap[$slug] = $cat;
                }
                break;
            }
            $remaining = $stillRemaining;
        }

        $this->command?->info('Total categories seeded: ' . count($categoryMap));

        // Remap any legacy company category_id to a valid WordPress category
        $fallbackCategory = Category::whereNotNull('parent_id')->whereNotNull('term_id')->first();
        \App\Models\Company::whereIn('category_id', function ($q) use ($pillarsConfig) {
            $q->select('id')->from('categories')->whereNull('term_id')->whereNotIn('slug', array_keys($pillarsConfig));
        })->update(['category_id' => $fallbackCategory?->id]);

        // Clean up old placeholder categories (children first, then parents)
        Category::whereNull('term_id')->whereNotIn('slug', array_keys($pillarsConfig))->whereNotNull('parent_id')->delete();
        Category::whereNull('term_id')->whereNotIn('slug', array_keys($pillarsConfig))->delete();

        $legacyCompanies = \App\Models\Company::all();
        $fallbackCategory = Category::whereNotNull('parent_id')->first();
        foreach ($legacyCompanies as $comp) {
            if (!$comp->category_id || !Category::find($comp->category_id)) {
                $comp->update(['category_id' => $fallbackCategory?->id]);
            }
            // Populate pivot company_categories if empty
            if ($comp->category_id && !$comp->categories()->where('category_id', $comp->category_id)->exists()) {
                $comp->categories()->attach($comp->category_id, ['is_primary' => true]);
            }
        }

        $this->command?->info('Category seeding and synchronization complete!');
    }
}
