<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $settings = [
            ['key' => 'payment_card_enabled', 'value' => '1', 'type' => 'select', 'group' => 'payment', 'label' => 'Credit/Debit Card'],
            ['key' => 'payment_paypal_enabled', 'value' => '1', 'type' => 'select', 'group' => 'payment', 'label' => 'PayPal'],
            ['key' => 'payment_invoice_enabled', 'value' => '1', 'type' => 'select', 'group' => 'payment', 'label' => 'Pay by Invoice'],
            ['key' => 'paypal_mode', 'value' => 'sandbox', 'type' => 'select', 'group' => 'payment', 'label' => 'PayPal Mode'],
            ['key' => 'paypal_client_id', 'value' => '', 'type' => 'text', 'group' => 'payment', 'label' => 'PayPal Client ID'],
            ['key' => 'paypal_secret', 'value' => '', 'type' => 'text', 'group' => 'payment', 'label' => 'PayPal Secret'],
            ['key' => 'paypal_currency', 'value' => 'GBP', 'type' => 'text', 'group' => 'payment', 'label' => 'PayPal Currency'],
        ];

        foreach ($settings as $s) {
            $exists = DB::table('site_settings')->where('key', $s['key'])->first();
            if (!$exists) {
                DB::table('site_settings')->insert(array_merge($s, ['created_at' => now(), 'updated_at' => now()]));
            }
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'payment_card_enabled', 'payment_paypal_enabled', 'payment_invoice_enabled',
            'paypal_mode', 'paypal_client_id', 'paypal_secret', 'paypal_currency',
        ])->delete();
    }
};
