<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ═══ 1. WISHLIST ═══
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('product_id');
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
                $table->unique(['user_id', 'product_id']);
            });
        }

        // ═══ 2. SAVED ADDRESSES ═══
        if (!Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('label')->default('Home'); // Home, Office, etc
                $table->string('first_name');
                $table->string('last_name');
                $table->string('company')->nullable();
                $table->string('phone')->nullable();
                $table->string('address_line1');
                $table->string('address_line2')->nullable();
                $table->string('city');
                $table->string('postcode');
                $table->string('country')->default('GB');
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }

        // ═══ 3. ORDER NOTES (customer <-> admin communication) ═══
        if (!Schema::hasTable('order_notes')) {
            Schema::create('order_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->text('message');
                $table->string('author_type'); // 'customer' or 'admin'
                $table->unsignedBigInteger('author_id')->nullable();
                $table->string('author_name')->nullable();
                $table->boolean('is_internal')->default(false); // admin-only note
                $table->timestamps();
                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        // ═══ 4. ADMIN ACTIVITY LOG ═══
        if (!Schema::hasTable('admin_activity_log')) {
            Schema::create('admin_activity_log', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->string('admin_name')->nullable();
                $table->string('action');           // "updated_order_status", "created_product", etc
                $table->string('entity_type')->nullable(); // "order", "product", "user"
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->text('details')->nullable(); // JSON or text description
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }

        // ═══ 5. Add reorder support columns ═══
        if (!Schema::hasColumn('orders', 'parent_order_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_order_id')->nullable()->after('id');
                $table->text('admin_notes')->nullable()->after('delivery_notes');
            });
        }

        // ═══ 6. User profile extras ═══
        if (!Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar')->nullable();
                $table->string('trade_account_number')->nullable();
                $table->boolean('email_notifications')->default(true);
                $table->boolean('sms_notifications')->default(false);
            });
        }

        // ═══ 7. Email template settings ═══
        $emailSettings = [
            ['key'=>'email_order_confirm_subject','value'=>'Order Confirmed — {{ order_ref }}','type'=>'text','group'=>'email','label'=>'Order Confirmation — Subject'],
            ['key'=>'email_order_confirm_header','value'=>'Thank you for your order!','type'=>'text','group'=>'email','label'=>'Order Confirmation — Header'],
            ['key'=>'email_order_confirm_footer','value'=>'If you have any questions, reply to this email or call us.','type'=>'textarea','group'=>'email','label'=>'Order Confirmation — Footer Text'],
            ['key'=>'email_status_update_subject','value'=>'Order Update — {{ order_ref }}','type'=>'text','group'=>'email','label'=>'Status Update — Subject'],
            ['key'=>'email_from_name','value'=>'London InstantPrint','type'=>'text','group'=>'email','label'=>'From Name'],
            ['key'=>'email_from_address','value'=>'orders@londoninstantprint.co.uk','type'=>'text','group'=>'email','label'=>'From Email'],
            ['key'=>'email_reply_to','value'=>'support@londoninstantprint.co.uk','type'=>'text','group'=>'email','label'=>'Reply-To Email'],
        ];
        foreach ($emailSettings as $s) {
            if (!DB::table('site_settings')->where('key', $s['key'])->exists()) {
                DB::table('site_settings')->insert(array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activity_log');
        Schema::dropIfExists('order_notes');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('wishlists');
    }
};
