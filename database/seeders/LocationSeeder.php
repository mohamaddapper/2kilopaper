<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/locations.csv');
        if (!File::exists($csvPath)) {
            $fallback = '/Users/md/Desktop/2K New Website/cls_inds_loc_loc-20261123.csv';
            if (File::exists($fallback)) {
                $csvPath = $fallback;
            } else {
                $this->command?->error("Locations CSV file not found at: {$csvPath}");
                return;
            }
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

        $this->command?->info('Processing ' . count($rawRows) . ' location rows from CSV...');

        // 1. Process Provinces
        $provinceMap = []; // slug => Province model
        $provinceSort = 1;
        foreach ($rawRows as $row) {
            if (empty($row['term_parent'])) {
                $slug = trim($row['term_slug']);
                $name = trim($row['term_title']);
                $termId = !empty($row['term_id']) ? (int)$row['term_id'] : null;
                $desc = !empty($row['term_content']) ? trim($row['term_content']) : null;

                $province = Province::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'term_id' => $termId,
                        'description' => $desc,
                        'sort' => $provinceSort++,
                    ]
                );
                $provinceMap[$slug] = $province;
            }
        }

        $this->command?->info('Seeded ' . count($provinceMap) . ' provinces.');

        // 2. Process Cities
        $cityCount = 0;
        $cityNameToCity = [];
        foreach ($rawRows as $row) {
            if (!empty($row['term_parent'])) {
                $parentSlug = trim($row['term_parent']);
                $province = $provinceMap[$parentSlug] ?? null;
                if (!$province) {
                    continue;
                }

                $slug = trim($row['term_slug']);
                $name = trim($row['term_title']);
                $termId = !empty($row['term_id']) ? (int)$row['term_id'] : null;
                $desc = !empty($row['term_content']) ? trim($row['term_content']) : null;

                $city = City::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'province_id' => $province->id,
                        'term_id' => $termId,
                        'description' => $desc,
                    ]
                );

                $cityNameToCity[$name] = $city;
                $cityCount++;
            }
        }

        $this->command?->info("Seeded {$cityCount} cities.");

        // 3. Clean up older placeholder cities without province_id (from legacy seeder)
        $oldCities = City::whereNull('province_id')->get();
        foreach ($oldCities as $old) {
            // Check if matching city exists with province
            if (isset($cityNameToCity[$old->name])) {
                $matched = $cityNameToCity[$old->name];
                Company::where('city_id', $old->id)->update([
                    'city_id' => $matched->id,
                    'province_id' => $matched->province_id,
                ]);
                $old->delete();
            }
        }

        // 4. Update any companies missing province_id
        $companies = Company::whereNull('province_id')->with('city.province')->get();
        foreach ($companies as $comp) {
            if ($comp->city && $comp->city->province_id) {
                $comp->update(['province_id' => $comp->city->province_id]);
            }
        }

        $this->command?->info('Locations successfully seeded and synchronized!');
    }
}
