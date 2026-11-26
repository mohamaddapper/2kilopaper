<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('company_affiliates')) {
            Schema::create('company_affiliates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('affiliate_id')->constrained('companies')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['company_id', 'affiliate_id']);
                $table->index('affiliate_id');
            });
        }

        $csvPath = database_path('data/companies.csv');
        if (!is_file($csvPath) || !Schema::hasTable('companies')) {
            return;
        }

        $dbCompanies = DB::table('companies')->select('id', 'slug', 'name')->get();
        $bySlug = [];
        $byName = [];
        foreach ($dbCompanies as $c) {
            if ($c->slug) {
                $bySlug[trim((string) $c->slug)] = (int) $c->id;
            }
            if ($c->name) {
                $byName[trim((string) $c->name)] = (int) $c->id;
            }
        }

        $h = fopen($csvPath, 'r');
        if ($h === false) {
            return;
        }

        $hdr = fgetcsv($h, 0, ',', '"', '\\') ?: [];
        $idxId = array_search('post_id', $hdr, true);
        $idxSlug = array_search('post_slug', $hdr, true);
        $idxTitle = array_search('post_title', $hdr, true);
        $idxAff = array_search('field_affiliate_company_id', $hdr, true);

        if ($idxId === false || $idxAff === false) {
            fclose($h);
            return;
        }

        $postIdToDbId = [];
        $rawAffiliates = [];

        while (($d = fgetcsv($h, 0, ',', '"', '\\')) !== false) {
            $pid = (int) ($d[$idxId] ?? 0);
            if ($pid <= 0) {
                continue;
            }
            $slug = $idxSlug !== false ? urldecode(trim((string) ($d[$idxSlug] ?? ''))) : '';
            $title = $idxTitle !== false ? trim((string) ($d[$idxTitle] ?? '')) : '';

            $dbId = $bySlug[$slug] ?? ($byName[$title] ?? null);
            if ($dbId) {
                $postIdToDbId[$pid] = $dbId;
            }

            $affRaw = trim((string) ($d[$idxAff] ?? ''));
            if ($affRaw !== '' && $dbId) {
                $rawAffiliates[] = [$dbId, $affRaw];
            }
        }
        fclose($h);

        $now = now();
        $pairs = [];

        foreach ($rawAffiliates as [$sourceDbId, $affRaw]) {
            $targetPostIds = preg_split('/[\s,;]+/', $affRaw, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($targetPostIds as $tPidRaw) {
                $tPid = (int) $tPidRaw;
                $targetDbId = $postIdToDbId[$tPid] ?? null;
                if (!$targetDbId || $targetDbId === $sourceDbId) {
                    continue;
                }
                $pairs["{$sourceDbId}_{$targetDbId}"] = [
                    'company_id' => $sourceDbId,
                    'affiliate_id' => $targetDbId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $pairs["{$targetDbId}_{$sourceDbId}"] = [
                    'company_id' => $targetDbId,
                    'affiliate_id' => $sourceDbId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($pairs)) {
            foreach (array_chunk(array_values($pairs), 200) as $chunk) {
                DB::table('company_affiliates')->insertOrIgnore($chunk);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_affiliates');
    }
};
