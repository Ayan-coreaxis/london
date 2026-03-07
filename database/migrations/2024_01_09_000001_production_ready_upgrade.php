<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ═══════════════════════════════════════════
        // 1. DELIVERY ZONES — admin-managed delivery options
        // ═══════════════════════════════════════════
        if (!Schema::hasTable('delivery_zones')) {
            Schema::create('delivery_zones', function (Blueprint $table) {
                $table->id();
                $table->string('name');                 // e.g. "Mainland UK", "London Zones", "Scotland & Highlands"
                $table->text('postcodes')->nullable();  // comma-sep prefixes: "EC,WC,E,W,N,NW,SE,SW"
                $table->string('country')->default('GB');
                $table->boolean('is_default')->default(false);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('delivery_methods')) {
            Schema::create('delivery_methods', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('zone_id')->nullable();
                $table->string('name');                 // "Next Day Delivery"
                $table->string('slug')->unique();       // "next_day"
                $table->text('description')->nullable(); // "Order before 12pm"
                $table->decimal('price', 10, 2)->default(0);
                $table->string('estimated_days')->nullable(); // "1-2 working days"
                $table->boolean('is_free')->default(false);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('zone_id')->references('id')->on('delivery_zones')->nullOnDelete();
            });
        }

        // ═══════════════════════════════════════════
        // 2. PAYMENT METHODS — fully dynamic
        // ═══════════════════════════════════════════
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');                 // "Credit/Debit Card"
                $table->string('slug')->unique();       // "stripe_card"
                $table->string('provider');              // "stripe", "paypal", "bank_transfer", "invoice"
                $table->text('description')->nullable();
                $table->string('icon')->nullable();     // SVG or image path
                $table->json('config')->nullable();     // provider-specific config keys
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_test_mode')->default(true);
                $table->timestamps();
            });
        }

        // ═══════════════════════════════════════════
        // 3. PAYMENT TRANSACTIONS — track all payment attempts
        // ═══════════════════════════════════════════
        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->string('payment_method');       // "stripe_card", "paypal"
                $table->string('transaction_id')->nullable(); // Stripe/PayPal ID
                $table->string('status');               // "pending","completed","failed","refunded"
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('GBP');
                $table->json('gateway_response')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        // ═══════════════════════════════════════════
        // 4. FOOTER LINKS — fully dynamic
        // ═══════════════════════════════════════════
        if (!Schema::hasTable('footer_links')) {
            Schema::create('footer_links', function (Blueprint $table) {
                $table->id();
                $table->string('column')->default('useful_links'); // "useful_links","payment_info","custom"
                $table->string('label');
                $table->string('url');
                $table->string('target')->default('_self'); // "_self" or "_blank"
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ═══════════════════════════════════════════
        // 5. NAV LINKS — dynamic navigation
        // ═══════════════════════════════════════════
        if (!Schema::hasTable('nav_links')) {
            Schema::create('nav_links', function (Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->string('url');
                $table->string('type')->default('header'); // "header","footer","mobile"
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ═══════════════════════════════════════════
        // 6. PROMO CODES
        // ═══════════════════════════════════════════
        if (!Schema::hasTable('promo_codes')) {
            Schema::create('promo_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('value', 10, 2);
                $table->decimal('min_order_amount', 10, 2)->default(0);
                $table->integer('max_uses')->nullable();
                $table->integer('used_count')->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ═══════════════════════════════════════════
        // 7. Add Stripe columns to orders
        // ═══════════════════════════════════════════
        if (!Schema::hasColumn('orders', 'stripe_payment_intent')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('stripe_payment_intent')->nullable()->after('payment_method');
                $table->string('paypal_order_id')->nullable()->after('stripe_payment_intent');
                $table->string('payment_status')->default('pending')->after('paypal_order_id');
                $table->string('promo_code')->nullable()->after('payment_status');
                $table->decimal('discount', 10, 2)->default(0)->after('promo_code');
            });
        }

        // ═══════════════════════════════════════════
        // 8. Add product accordion text columns
        // ═══════════════════════════════════════════
        if (!Schema::hasColumn('products', 'artwork_setup_text')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('artwork_setup_text')->nullable();
                $table->text('artwork_templates_text')->nullable();
                $table->text('technical_spec_text')->nullable();
                $table->text('key_info_text')->nullable();
            });
        }

        // ═══════════════════════════════════════════
        // SEED: Default delivery zones & methods
        // ═══════════════════════════════════════════
        $zoneMainland = DB::table('delivery_zones')->insertGetId([
            'name' => 'Mainland UK', 'postcodes' => '', 'country' => 'GB',
            'is_default' => true, 'sort_order' => 1, 'is_active' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $zoneLondon = DB::table('delivery_zones')->insertGetId([
            'name' => 'London Postcodes', 'postcodes' => 'EC,WC,E,W,N,NW,SE,SW,EN,HA,IG,KT,RM,SM,TW,UB,BR,CR,DA',
            'country' => 'GB', 'is_default' => false, 'sort_order' => 2, 'is_active' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $zoneScotland = DB::table('delivery_zones')->insertGetId([
            'name' => 'Scotland & Highlands', 'postcodes' => 'AB,DD,DG,EH,FK,G,HS,IV,KA,KW,KY,ML,PA,PH,TD,ZE',
            'country' => 'GB', 'is_default' => false, 'sort_order' => 3, 'is_active' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $zoneIreland = DB::table('delivery_zones')->insertGetId([
            'name' => 'Northern Ireland', 'postcodes' => 'BT',
            'country' => 'GB', 'is_default' => false, 'sort_order' => 4, 'is_active' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $methods = [
            ['zone_id'=>null,'name'=>'Free Next Day Delivery','slug'=>'next_day_free','description'=>'Order before 12pm for next working day delivery','price'=>0,'estimated_days'=>'1 working day','is_free'=>true,'sort_order'=>1,'is_active'=>true],
            ['zone_id'=>$zoneLondon,'name'=>'Same Day Rush (London)','slug'=>'same_day_london','description'=>'London postcodes only — order before 10am','price'=>19.99,'estimated_days'=>'Same day','is_free'=>false,'sort_order'=>2,'is_active'=>true],
            ['zone_id'=>null,'name'=>'Standard Delivery','slug'=>'standard','description'=>'3-5 working days','price'=>0,'estimated_days'=>'3-5 working days','is_free'=>true,'sort_order'=>3,'is_active'=>true],
            ['zone_id'=>$zoneScotland,'name'=>'Scotland & Highlands','slug'=>'scotland_highlands','description'=>'2-3 working days','price'=>4.99,'estimated_days'=>'2-3 working days','is_free'=>false,'sort_order'=>4,'is_active'=>true],
            ['zone_id'=>$zoneIreland,'name'=>'Northern Ireland','slug'=>'northern_ireland','description'=>'2-3 working days','price'=>5.99,'estimated_days'=>'2-3 working days','is_free'=>false,'sort_order'=>5,'is_active'=>true],
        ];
        foreach ($methods as $m) {
            DB::table('delivery_methods')->insert(array_merge($m, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // Seed: Default payment methods
        $payMethods = [
            ['name'=>'Credit / Debit Card (Stripe)','slug'=>'stripe_card','provider'=>'stripe','description'=>'Pay securely with Visa, Mastercard, Amex','icon'=>'card','config'=>json_encode(['public_key'=>'','secret_key'=>'','webhook_secret'=>'']),'sort_order'=>1,'is_active'=>true,'is_test_mode'=>true],
            ['name'=>'PayPal','slug'=>'paypal','provider'=>'paypal','description'=>'Pay with your PayPal account','icon'=>'paypal','config'=>json_encode(['client_id'=>'','secret'=>'','mode'=>'sandbox','currency'=>'GBP']),'sort_order'=>2,'is_active'=>true,'is_test_mode'=>true],
            ['name'=>'Bank Transfer','slug'=>'bank_transfer','provider'=>'manual','description'=>'Pay by direct bank transfer. Order processed after payment received.','icon'=>'bank','config'=>json_encode(['bank_name'=>'','account_name'=>'','sort_code'=>'','account_number'=>'','reference_prefix'=>'LIP-']),'sort_order'=>3,'is_active'=>false,'is_test_mode'=>false],
            ['name'=>'Pay by Invoice (Trade)','slug'=>'invoice','provider'=>'manual','description'=>'For approved trade accounts only. Net 30 payment terms.','icon'=>'invoice','config'=>json_encode(['terms_days'=>30]),'sort_order'=>4,'is_active'=>true,'is_test_mode'=>false],
        ];
        foreach ($payMethods as $pm) {
            DB::table('payment_methods')->insert(array_merge($pm, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // Seed: Default footer links
        $footerLinks = [
            ['column'=>'useful_links','label'=>'Login / Register','url'=>'/login','sort_order'=>1],
            ['column'=>'useful_links','label'=>'About Us','url'=>'/about-us','sort_order'=>2],
            ['column'=>'useful_links','label'=>'Contact Us','url'=>'/contact-us','sort_order'=>3],
            ['column'=>'useful_links','label'=>'All Products','url'=>'/all-products','sort_order'=>4],
            ['column'=>'useful_links','label'=>'Blog','url'=>'/blog','sort_order'=>5],
            ['column'=>'useful_links','label'=>'Terms & Conditions','url'=>'/terms-conditions','sort_order'=>6],
            ['column'=>'useful_links','label'=>'Privacy Policy','url'=>'/privacy-policy','sort_order'=>7],
            ['column'=>'useful_links','label'=>'FAQ','url'=>'/contact-us','sort_order'=>8],
        ];
        foreach ($footerLinks as $fl) {
            DB::table('footer_links')->insert(array_merge($fl, ['target'=>'_self','is_active'=>true,'created_at'=>now(),'updated_at'=>now()]));
        }

        // Seed: Default nav links
        $navLinks = [
            ['label'=>'All Products','url'=>'/all-products','type'=>'header','sort_order'=>1],
            ['label'=>'Banners','url'=>'/banners','type'=>'header','sort_order'=>2],
            ['label'=>'Business Cards','url'=>'/product/business-cards','type'=>'header','sort_order'=>3],
            ['label'=>'Brochures & Booklets','url'=>'/brochures','type'=>'header','sort_order'=>4],
        ];
        foreach ($navLinks as $nl) {
            DB::table('nav_links')->insert(array_merge($nl, ['parent_id'=>null,'is_active'=>true,'created_at'=>now(),'updated_at'=>now()]));
        }

        // Add new site settings for Stripe keys
        $newSettings = [
            ['key'=>'stripe_public_key','value'=>'','type'=>'text','group'=>'payment','label'=>'Stripe Publishable Key'],
            ['key'=>'stripe_secret_key','value'=>'','type'=>'text','group'=>'payment','label'=>'Stripe Secret Key'],
            ['key'=>'stripe_webhook_secret','value'=>'','type'=>'text','group'=>'payment','label'=>'Stripe Webhook Secret'],
            ['key'=>'stripe_mode','value'=>'test','type'=>'select','group'=>'payment','label'=>'Stripe Mode'],
        ];
        foreach ($newSettings as $s) {
            if (!DB::table('site_settings')->where('key', $s['key'])->exists()) {
                DB::table('site_settings')->insert(array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_links');
        Schema::dropIfExists('nav_links');
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('delivery_methods');
        Schema::dropIfExists('delivery_zones');
    }
};
