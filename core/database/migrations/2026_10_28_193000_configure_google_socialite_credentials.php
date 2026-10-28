<?php

use App\Constants\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $clientId     = '689451243598-pfpp0384omt30as3lcrrte69ksgb36b3.apps.googleusercontent.com';
        $clientSecret = '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';

        $setting = DB::table('general_settings')->first();
        if ($setting) {
            $credentials = json_decode($setting->socialite_credentials, true);
            if (!is_array($credentials)) {
                $credentials = [];
            }

            $credentials['google'] = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'status'        => Status::ENABLE,
            ];

            DB::table('general_settings')->where('id', $setting->id)->update([
                'socialite_credentials' => json_encode($credentials),
                'updated_at'            => now(),
            ]);
        }
    }

    public function down(): void
    {
        $setting = DB::table('general_settings')->first();
        if ($setting) {
            $credentials = json_decode($setting->socialite_credentials, true);
            if (is_array($credentials) && isset($credentials['google'])) {
                $credentials['google']['status'] = Status::DISABLE;
                DB::table('general_settings')->where('id', $setting->id)->update([
                    'socialite_credentials' => json_encode($credentials),
                    'updated_at'            => now(),
                ]);
            }
        }
    }
};
