<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('companies') || !Schema::hasColumn('companies', 'views_count')) {
            return;
        }

        $sqlPath = database_path('data/wp_post_views.sql');
        $csvReportPath = database_path('data/pvc_views_report.csv');
        $companiesCsvPath = database_path('data/companies.csv');

        if (!is_file($sqlPath) && !is_file($csvReportPath)) {
            return;
        }

        $sqlViews = [];
        if (is_file($sqlPath)) {
            $sqlContent = file_get_contents($sqlPath);
            if ($sqlContent && preg_match_all('/\((\d+),\s*(\d+)\)/', $sqlContent, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $row) {
                    $sqlViews[(int) $row[1]] = (int) $row[2];
                }
            }
        }

        $csvViews = [];
        if (is_file($csvReportPath) && ($h = fopen($csvReportPath, 'r')) !== false) {
            fgetcsv($h, 0, ',', '"', '\\');
            while (($d = fgetcsv($h, 0, ',', '"', '\\')) !== false) {
                $title = trim((string) ($d[0] ?? ''));
                $type = (string) ($d[1] ?? '');
                if ($title !== '' && isset($d[2]) && str_contains($type, 'شرکت')) {
                    $csvViews[$title] = (int) $d[2];
                }
            }
            fclose($h);
        }

        $viewsBySlug = [];
        $viewsByTitle = $csvViews;

        if (is_file($companiesCsvPath) && ($ch = fopen($companiesCsvPath, 'r')) !== false) {
            $hdr = fgetcsv($ch, 0, ',', '"', '\\') ?: [];
            $idxId = array_search('post_id', $hdr, true);
            $idxSlug = array_search('post_slug', $hdr, true);
            $idxTitle = array_search('post_title', $hdr, true);

            while (($d = fgetcsv($ch, 0, ',', '"', '\\')) !== false) {
                $pid = $idxId !== false ? (int) ($d[$idxId] ?? 0) : 0;
                $slug = $idxSlug !== false ? urldecode(trim((string) ($d[$idxSlug] ?? ''))) : '';
                $title = $idxTitle !== false ? trim((string) ($d[$idxTitle] ?? '')) : '';

                $v = $sqlViews[$pid] ?? ($csvViews[$title] ?? null);
                if ($v !== null && $v > 0) {
                    if ($slug !== '') {
                        $viewsBySlug[$slug] = max($viewsBySlug[$slug] ?? 0, $v);
                    }
                    if ($title !== '') {
                        $viewsByTitle[$title] = max($viewsByTitle[$title] ?? 0, $v);
                    }
                }
            }
            fclose($ch);
        }

        $companies = DB::table('companies')->select('id', 'slug', 'name', 'views_count')->get();
        foreach ($companies as $company) {
            $legacyViews = $viewsBySlug[$company->slug] ?? ($viewsByTitle[trim((string) $company->name)] ?? 0);
            if ($legacyViews > 0) {
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update(['views_count' => (int) ($company->views_count ?? 0) + $legacyViews]);
            }
        }
    }

    public function down(): void
    {
        // Non-destructive: do not reset views_count on rollback.
    }
};
