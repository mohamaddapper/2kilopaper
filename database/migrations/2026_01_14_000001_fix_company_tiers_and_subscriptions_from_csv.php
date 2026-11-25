<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('companies') || !Schema::hasColumn('companies', 'subscription_plan')) {
            return;
        }

        $csvPath = database_path('data/companies.csv');
        if (!File::exists($csvPath)) {
            return;
        }

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            return;
        }

        $header = fgetcsv($handle, null, ',', '"', '\\');
        if (!$header) {
            fclose($handle);
            return;
        }

        while (($line = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
            if (count($line) < count($header)) {
                $line = array_pad($line, count($header), '');
            } elseif (count($line) > count($header)) {
                $line = array_slice($line, 0, count($header));
            }
            $row = array_combine($header, $line);
            if (!$row) {
                continue;
            }

            $featVal = trim((string) ($row['entity_featured__value'] ?? ''));
            $planId = trim((string) ($row['payment_plan__plan_id'] ?? ''));
            if ($featVal === '' && $planId === '') {
                continue;
            }

            $name = trim((string) ($row['directory_title'] ?? ($row['post_title'] ?? '')));
            if ($name === '') {
                continue;
            }

            $tier = null;
            $tierExpiresAt = null;
            if ($featVal === '9') {
                $tier = 'gold';
            } elseif ($featVal === '5') {
                $tier = 'silver';
            } elseif ($featVal === '1') {
                $tier = 'bronze';
            }

            $featExp = (int) ($row['entity_featured__expires_at'] ?? 0);
            if ($tier) {
                $tierExpiresAt = ($featExp > 1000000000)
                    ? Carbon::createFromTimestamp($featExp)
                    : now()->addMonths(6);
            }

            $subscriptionPlan = null;
            $subscriptionExpiresAt = null;
            if ($planId === '8697') {
                $subscriptionPlan = '12m';
            } elseif ($planId === '8692') {
                $subscriptionPlan = '6m';
            } elseif ($planId !== '') {
                $subscriptionPlan = '3m';
            }

            $planExp = (int) ($row['payment_plan__expires_at'] ?? 0);
            if ($subscriptionPlan) {
                $monthsMap = ['12m' => 12, '6m' => 6, '3m' => 3];
                $subscriptionExpiresAt = ($planExp > 1000000000)
                    ? Carbon::createFromTimestamp($planExp)
                    : now()->addMonths($monthsMap[$subscriptionPlan]);
            }

            DB::table('companies')
                ->where('name', $name)
                ->update([
                    'tier' => $tier,
                    'tier_expires_at' => $tierExpiresAt,
                    'subscription_plan' => $subscriptionPlan,
                    'subscription_expires_at' => $subscriptionExpiresAt,
                ]);
        }

        fclose($handle);
    }

    public function down(): void
    {
        //
    }
};
