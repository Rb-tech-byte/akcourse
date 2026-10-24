<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'license_key')) {
                $table->string('license_key', 64)->nullable()->after('purchase_code')->index();
            }
            if (!Schema::hasColumn('order_items', 'certificate_number')) {
                $table->string('certificate_number', 64)->nullable()->after('license_key')->index();
            }
        });

        // Backfill existing order items with auto-generated clean license key and certificate number
        $items = DB::table('order_items')->whereNull('license_key')->orWhere('license_key', '')->get();
        foreach ($items as $item) {
            $year = $item->created_at ? date('Y', strtotime($item->created_at)) : date('Y');
            $part1 = strtoupper(substr(md5($item->id . 'lic_salt_1' . $item->purchase_code), 0, 4));
            $part2 = strtoupper(substr(md5($item->id . 'lic_salt_2' . $item->user_id), 0, 4));
            $part3 = strtoupper(substr(md5($item->id . 'lic_salt_3' . $item->product_id), 0, 4));
            $licenseKey = "AK23-LIC-{$part1}-{$part2}-{$part3}";

            $cert1 = strtoupper(substr(md5($item->id . 'cert_salt_1' . $item->purchase_code), 0, 4));
            $cert2 = strtoupper(substr(md5($item->id . 'cert_salt_2' . $item->created_at), 0, 4));
            $certNum = "AK23-CERT-{$year}-{$cert1}-{$cert2}";

            DB::table('order_items')->where('id', $item->id)->update([
                'license_key' => $licenseKey,
                'certificate_number' => $certNum,
                'license' => 1, // Standardized 1x One-Time Perpetual Lifetime License
                'is_extended' => 0,
                'extended_amount' => 0,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'license_key')) {
                $table->dropColumn('license_key');
            }
            if (Schema::hasColumn('order_items', 'certificate_number')) {
                $table->dropColumn('certificate_number');
            }
        });
    }
};
