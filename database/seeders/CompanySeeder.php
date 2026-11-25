<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/companies.csv');
        if (!File::exists($csvPath)) {
            $this->command?->error("Companies CSV not found at {$csvPath}");
            return;
        }

        // 0. Ensure columns in companies table are expanded
        try {
            DB::statement("ALTER TABLE companies MODIFY phone VARCHAR(255) NULL");
            DB::statement("ALTER TABLE companies MODIFY address TEXT NULL");
        } catch (\Throwable $e) {
            // Ignore on SQLite or if already expanded
        }

        // 1. Get or create system / admin user
        $adminUser = User::where('is_admin', true)->first();
        if (!$adminUser) {
            $adminUser = User::firstOrCreate(
                ['phone' => '09120000000'],
                ['name' => 'مدیریت سایت ۲ کیلو کاغذ', 'is_admin' => true]
            );
        }

        // 2. Preload Categories
        $categoriesBySlug = Category::all()->keyBy('slug');
        $defaultCategory = Category::first();

        // 3. Preload Provinces and Cities
        $provincesBySlug = Province::all()->keyBy('slug');
        $provincesByName = Province::all()->keyBy('name');
        $citiesBySlug = City::all()->keyBy('slug');
        $citiesByName = City::all()->keyBy('name');
        $defaultCity = City::first();
        $defaultProvince = Province::first();

        // 4. Read CSV rows
        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->command?->error("Cannot open {$csvPath}");
            return;
        }

        $header = fgetcsv($handle, null, ',', '"', '\\');
        $rows = [];
        while (($line = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
            if (count($line) === count($header)) {
                $rows[] = array_combine($header, $line);
            }
        }
        fclose($handle);

        $totalRows = count($rows);
        $this->command?->info("Importing {$totalRows} companies from CSV...");

        $imported = 0;
        $updated = 0;
        $existingSlugs = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $name = mb_substr(trim($row['post_title'] ?? ''), 0, 190);
                if (!$name) {
                    continue;
                }

                // Slug handling
                $rawSlug = trim($row['post_slug'] ?? '');
                if ($rawSlug) {
                    $slug = urldecode($rawSlug);
                } else {
                    $slug = Str::slug($name);
                    if (!$slug) {
                        $slug = preg_replace('/\s+/u', '-', trim($name));
                    }
                    if (!$slug) {
                        $slug = 'company-' . ($row['post_id'] ?? ($index + 1));
                    }
                }
                $slug = mb_substr($slug, 0, 180);
                // Ensure slug uniqueness
                $baseSlug = $slug;
                $counter = 1;
                while (isset($existingSlugs[$slug])) {
                    $slug = mb_substr($baseSlug, 0, 170) . '-' . (++$counter);
                }
                $existingSlugs[$slug] = true;

                // Status
                $postStatus = trim($row['post_status'] ?? 'publish');
                $status = ($postStatus === 'trash') ? 'rejected' : (($postStatus === 'draft') ? 'pending' : 'approved');

                // Media: Logo
                $logoPath = null;
                $logoName = trim($row['field_business_logo'] ?? '');
                if ($logoName) {
                    if (File::exists(public_path('uploads/logos/' . $logoName))) {
                        $logoPath = 'uploads/logos/' . $logoName;
                    }
                }

                // Media: Catalog
                $catalogPath = null;
                $catalogName = trim($row['field_catalog_of_products_and_services'] ?? '');
                if ($catalogName) {
                    if (File::exists(public_path('uploads/catalogs/' . $catalogName))) {
                        $catalogPath = 'uploads/catalogs/' . $catalogName;
                    }
                }

                // Media: Gallery Photos
                $gallery = [];
                $photosStr = trim($row['directory_photos'] ?? '');
                if ($photosStr) {
                    foreach (explode(';', $photosStr) as $photo) {
                        $pName = trim($photo);
                        if ($pName && File::exists(public_path('uploads/gallery/' . $pName))) {
                            $gallery[] = 'uploads/gallery/' . $pName;
                        }
                    }
                }

                // Tier (ویژه تبلیغاتی) & Subscription (اشتراک)
                $tier = null;
                $tierExpiresAt = null;
                $featVal = trim($row['entity_featured__value'] ?? '');

                if ($featVal === '9') {
                    $tier = 'gold';
                } elseif ($featVal === '5') {
                    $tier = 'silver';
                } elseif ($featVal === '1') {
                    $tier = 'bronze';
                }

                $featExp = (int)($row['entity_featured__expires_at'] ?? 0);
                if ($tier) {
                    $tierExpiresAt = ($featExp > 1000000000)
                        ? Carbon::createFromTimestamp($featExp)
                        : now()->addMonths(6);
                }

                $subscriptionPlan = null;
                $subscriptionExpiresAt = null;
                $planId = trim($row['payment_plan__plan_id'] ?? '');
                if ($planId === '8697') {
                    $subscriptionPlan = '12m';
                } elseif ($planId === '8692') {
                    $subscriptionPlan = '6m';
                } elseif ($planId !== '') {
                    $subscriptionPlan = '3m';
                }

                $planExp = (int)($row['payment_plan__expires_at'] ?? 0);
                if ($subscriptionPlan) {
                    $monthsMap = ['12m' => 12, '6m' => 6, '3m' => 3];
                    $subscriptionExpiresAt = ($planExp > 1000000000)
                        ? Carbon::createFromTimestamp($planExp)
                        : now()->addMonths($monthsMap[$subscriptionPlan]);
                }

                // Video
                $videoCode = trim($row['field_aparat_video_code'] ?? '') ?: trim($row['field_video'] ?? '');
                $videoCode = $videoCode ? mb_substr($videoCode, 0, 250) : null;

                // Phone, Email, Website
                $phone = trim($row['field_phone'] ?? '');
                $phone = $phone ? mb_substr($phone, 0, 250) : null;
                $email = filter_var(trim($row['field_email'] ?? ''), FILTER_VALIDATE_EMAIL) ?: null;
                $email = $email ? mb_substr($email, 0, 95) : null;
                $website = trim($row['field_website'] ?? '');
                if ($website && !preg_match('#^https?://#i', $website)) {
                    $website = 'https://' . $website;
                }
                $website = $website ? mb_substr($website, 0, 250) : null;

                // Tech Specs
                $techSpecs = array_filter([
                    'established_year' => trim($row['field_year_of_establishment'] ?? ''),
                    'certificates' => trim($row['field_certificates_received'] ?? ''),
                    'production_capacity' => trim($row['field_production_capacity'] ?? ''),
                    'production_grammage' => trim($row['field_production_grammage'] ?? ''),
                    'production_width' => trim($row['field_production_width'] ?? ''),
                    'machinery_type' => trim($row['field_type_or_brand_of_machinery'] ?? ''),
                    'carton_machinery' => trim($row['field_types_of_carton_making_machinery'] ?? ''),
                    'corrugated_machinery' => trim($row['field_types_of_corrugated_board_machinery'] ?? ''),
                    'custom_products' => trim($row['field_is_it_possible_to_produce_custom_products'] ?? ''),
                    'min_order' => trim($row['field_minimum_order_quantity'] ?? ''),
                ]);

                // Contacts
                $ceoName = trim(($row['field_name_and_surname_of_the_ceo__first_name'] ?? '') . ' ' . ($row['field_name_and_surname_of_the_ceo__last_name'] ?? ''));
                $salesName = trim(($row['field_sales_managers_name_and_surname__first_name'] ?? '') . ' ' . ($row['field_sales_managers_name_and_surname__last_name'] ?? ''));
                $ownerName = trim(($row['field_first_and_last_name_of_the_business_owner__first_name'] ?? '') . ' ' . ($row['field_first_and_last_name_of_the_business_owner__last_name'] ?? ''));

                $contacts = array_filter([
                    'ceo_name' => $ceoName ?: null,
                    'ceo_phone' => trim($row['field_ceo_contact_number'] ?? '') ?: null,
                    'sales_name' => $salesName ?: null,
                    'sales_phone' => trim($row['field_sales_manager_contact_number'] ?? '') ?: null,
                    'owner_name' => $ownerName ?: null,
                ]);

                // Socials
                $socials = array_filter([
                    'whatsapp' => trim($row['field_whatsapp'] ?? '') ?: null,
                    'telegram' => trim($row['field_telegram_number'] ?? '') ?: (trim($row['field_social_accounts__telegram'] ?? '') ?: null),
                    'instagram' => trim($row['field_social_accounts__instagram'] ?? '') ?: null,
                    'linkedin' => trim($row['field_social_accounts__linkedin'] ?? '') ?: null,
                ]);

                // Services tags
                $servicesList = [];
                foreach (['field_corrugated_and_carton_box_products_service', 'field_printing_services', 'field_post_press_services'] as $srvCol) {
                    $val = trim($row[$srvCol] ?? '');
                    if ($val) {
                        foreach (explode(';', $val) as $item) {
                            $item = trim($item);
                            if ($item && !in_array($item, $servicesList)) {
                                $servicesList[] = $item;
                            }
                        }
                    }
                }

                // Locations parsing
                $termIds = explode(';', $row['location_address__term_id'] ?? '');
                $streets = explode(';', $row['location_address__street'] ?? '');
                $provinces = explode(';', $row['location_address__province'] ?? '');
                $cities = explode(';', $row['location_address__city'] ?? '');

                $locations = [];
                $primaryProvinceId = null;
                $primaryCityId = null;
                $primaryAddress = null;

                $locCount = max(count($termIds), 1);
                for ($i = 0; $i < $locCount; $i++) {
                    $term = trim($termIds[$i] ?? '');
                    $street = trim($streets[$i] ?? '');
                    $provName = trim($provinces[$i] ?? '');
                    $cityName = trim($cities[$i] ?? '');

                    if (!$term && !$street && !$provName && !$cityName) {
                        continue;
                    }

                    $locCity = $citiesBySlug[$term] ?? null;
                    $locProvince = null;

                    if ($locCity) {
                        $locProvince = Province::find($locCity->province_id);
                    } else {
                        // Check if term is province slug
                        $locProvince = $provincesBySlug[$term] ?? null;
                    }

                    // Fallback to name search if term not found
                    if (!$locProvince && $provName) {
                        $cleanProvName = str_replace('استان ', '', $provName);
                        $locProvince = $provincesByName[$cleanProvName] ?? ($provincesByName[$provName] ?? null);
                    }
                    if (!$locCity && $cityName) {
                        $cleanCityName = str_replace('شهر ', '', $cityName);
                        $locCity = $citiesByName[$cleanCityName] ?? ($citiesByName[$cityName] ?? null);
                    }

                    $provId = $locProvince?->id ?? ($locCity?->province_id ?? $defaultProvince?->id);
                    $cityId = $locCity?->id ?? $defaultCity?->id;

                    if ($i === 0) {
                        $primaryProvinceId = $provId;
                        $primaryCityId = $cityId;
                        $primaryAddress = $street ?: ($row['location_address__address'] ?? null);
                    }

                    $locations[] = [
                        'type' => ($i === 0) ? 'factory' : 'central_office',
                        'title' => ($i === 0) ? 'محل کارخانه / دفتر اصلی' : 'دفتر مرکزی / شعبه',
                        'province_id' => $provId,
                        'city_id' => $cityId,
                        'address' => $street,
                        'phone' => $phone,
                        'is_primary' => ($i === 0),
                    ];
                }

                if (!$primaryCityId) {
                    $primaryCityId = $defaultCity?->id;
                    $primaryProvinceId = $defaultProvince?->id;
                }

                // Categories parsing
                $catSlugs = explode(';', $row['directory_category'] ?? '');
                $matchedCatIds = [];
                foreach ($catSlugs as $cSlug) {
                    $cSlug = trim($cSlug);
                    if ($cSlug && isset($categoriesBySlug[$cSlug])) {
                        $matchedCatIds[] = $categoriesBySlug[$cSlug]->id;
                    }
                }
                $matchedCatIds = array_values(array_unique($matchedCatIds));
                $primaryCatId = !empty($matchedCatIds) ? $matchedCatIds[0] : ($defaultCategory?->id ?? 1);

                // Create or Update Company
                $company = Company::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'user_id' => $adminUser->id,
                        'category_id' => $primaryCatId,
                        'province_id' => $primaryProvinceId,
                        'city_id' => $primaryCityId,
                        'name' => $name,
                        'status' => $status,
                        'description' => trim($row['post_content'] ?? ''),
                        'phone' => $phone ?: null,
                        'email' => $email,
                        'website' => $website ?: null,
                        'address' => $primaryAddress ?: null,
                        'locations' => !empty($locations) ? $locations : null,
                        'logo' => $logoPath,
                        'catalog' => $catalogPath,
                        'gallery' => !empty($gallery) ? $gallery : null,
                        'tier' => $tier,
                        'tier_expires_at' => $tierExpiresAt,
                        'subscription_plan' => $subscriptionPlan,
                        'subscription_expires_at' => $subscriptionExpiresAt,
                        'video_code' => $videoCode ?: null,
                        'contacts' => !empty($contacts) ? $contacts : null,
                        'tech_specs' => !empty($techSpecs) ? $techSpecs : null,
                        'socials' => !empty($socials) ? $socials : null,
                        'services' => !empty($servicesList) ? $servicesList : null,
                    ]
                );

                // Sync categories in pivot table
                if (!empty($matchedCatIds)) {
                    $syncData = [];
                    foreach ($matchedCatIds as $k => $cId) {
                        $syncData[$cId] = ['is_primary' => ($k === 0)];
                    }
                    $company->categories()->sync($syncData);
                }

                $imported++;
            }

            DB::commit();
            $this->command?->info("Successfully imported/synced {$imported} companies!");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command?->error("Import error: " . $e->getMessage());
            throw $e;
        }
    }
}
