<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create payment_methods table if it doesn't exist
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('provider');
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->json('config')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_test_mode')->default(true);
                $table->timestamps();
            });
        }

        // Insert default payment methods if table is empty
        $count = DB::table('payment_methods')->count();
        if ($count === 0) {
            $methods = [
                [
                    'name'        => 'Credit / Debit Card (Stripe)',
                    'slug'        => 'stripe_card',
                    'provider'    => 'stripe',
                    'description' => 'Pay securely with Visa, Mastercard, Amex',
                    'icon'        => 'card',
                    'config'      => json_encode([
                        'public_key'     => '',
                        'secret_key'     => '',
                        'webhook_secret' => '',
                    ]),
                    'sort_order'   => 1,
                    'is_active'    => true,
                    'is_test_mode' => true,
                ],
                [
                    'name'        => 'PayPal',
                    'slug'        => 'paypal',
                    'provider'    => 'paypal',
                    'description' => 'Pay with your PayPal account',
                    'icon'        => 'paypal',
                    'config'      => json_encode([
                        'client_id' => '',
                        'secret'    => '',
                        'mode'      => 'sandbox',
                        'currency'  => 'GBP',
                    ]),
                    'sort_order'   => 2,
                    'is_active'    => true,
                    'is_test_mode' => true,
                ],
                [
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
                    'sort_order'   => 3,
                    'is_active'    => true,   // ← ENABLED (was false before)
                    'is_test_mode' => false,
                ],
                [
                    'name'        => 'Pay by Invoice (Trade)',
                    'slug'        => 'invoice',
                    'provider'    => 'manual',
                    'description' => 'For approved trade accounts only. Net 30 payment terms.',
                    'icon'        => 'invoice',
                    'config'      => json_encode(['terms_days' => 30]),
                    'sort_order'   => 4,
                    'is_active'    => true,
                    'is_test_mode' => false,
                ],
            ];

            foreach ($methods as $m) {
                DB::table('payment_methods')->insert(array_merge($m, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        } else {
            // Table exists but bank_transfer might be disabled — enable it
            DB::table('payment_methods')
                ->where('slug', 'bank_transfer')
                ->update(['is_active' => true, 'updated_at' => now()]);

            // Make sure bank_transfer has full config structure
            $bt = DB::table('payment_methods')->where('slug', 'bank_transfer')->first();
            if ($bt) {
                $cfg = json_decode($bt->config ?? '{}', true) ?? [];
                $cfg = array_merge([
                    'bank_name'        => '',
                    'account_name'     => '',
                    'sort_code'        => '',
                    'account_number'   => '',
                    'reference_prefix' => 'LIP-',
                ], $cfg);
                DB::table('payment_methods')
                    ->where('slug', 'bank_transfer')
                    ->update(['config' => json_encode($cfg), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        // Just disable bank_transfer again on rollback
        if (Schema::hasTable('payment_methods')) {
            DB::table('payment_methods')
                ->where('slug', 'bank_transfer')
                ->update(['is_active' => false]);
        }
    }
};