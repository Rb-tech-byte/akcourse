<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('languages')->where('code', 'sw')->exists();
        if (!$exists) {
            DB::table('languages')->insert([
                'name'       => 'Kiswahili',
                'code'       => 'sw',
                'is_default' => 0,
                'image'      => 'sw.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('languages')->where('code', 'sw')->delete();
    }
};
