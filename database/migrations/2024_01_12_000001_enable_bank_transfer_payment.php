<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_methods')) return;

        // Enable Bank Transfer so checkout works without Stripe/PayPal API keys
        DB::table('payment_methods')
            ->where('slug', 'bank_transfer')
            ->update(['is_active' => true, 'updated_at' => now()]);

        // Insert if it doesn't exist yet
        $exists = DB::table('payment_methods')->where('slug', 'bank_transfer')->exists();
        if (!$exists) {
            DB::table('payment_methods')->insert([
                'name'        => 'Bank Transfer',
                'slug'        => 'bank_transfer',
                'provider'    => 'manual',
                'description' => 'Pay by direct bank transfer. Order processed after payment received.',
                'icon'        => 'bank',
                'config'      => json_encode([
                    'bank_name'        => '',
                    'account_name'     => '',
                    'sort_code'        => '',
                    'account_number'   => '',
                    'reference_prefix' => 'LIP-',
                ]),
                'sort_order'    => 3,
                'is_active'     => true,
                'is_test_mode'  => false,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('payment_methods')) return;
        DB::table('payment_methods')->where('slug', 'bank_transfer')->update(['is_active' => false]);
    }
};
