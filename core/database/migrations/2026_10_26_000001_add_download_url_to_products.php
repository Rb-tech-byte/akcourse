<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // External download URL (Google Drive, Dropbox, OneDrive, direct URL, etc.)
            // Takes priority over the uploaded file when set.
            $table->string('download_url', 2048)->nullable()->after('file');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('download_url');
        });
    }
};
