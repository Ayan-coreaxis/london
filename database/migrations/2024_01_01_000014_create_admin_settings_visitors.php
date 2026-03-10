<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('email');
                $table->string('company')->nullable()->after('phone');
            });
        }
        if (!Schema::hasTable('admin_users')) Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['superadmin','admin'])->default('admin');
            $table->rememberToken();
            $table->timestamps();
        });
        if (!Schema::hasTable('site_settings')) Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
        if (!Schema::hasTable('page_visitors')) Schema::create('page_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('page')->nullable();
            $table->string('user_agent')->nullable();
            $table->date('visited_at');
            $table->timestamps();
        });
        DB::table('admin_users')->insert([
            'name' => 'Super Admin', 'email' => 'admin@londoninstantprint.co.uk',
            'password' => Hash::make('Admin@1234'), 'role' => 'superadmin',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $settings = [
            ['key'=>'site_name','value'=>'London InstantPrint','group'=>'general'],
            ['key'=>'site_tagline','value'=>'Professional Printing Services UK','group'=>'general'],
            ['key'=>'site_email','value'=>'info@londoninstantprint.co.uk','group'=>'general'],
            ['key'=>'site_phone','value'=>'+44 20 0000 0000','group'=>'general'],
            ['key'=>'site_address','value'=>'London, United Kingdom','group'=>'general'],
            ['key'=>'hero_title','value'=>'Professional Print, Delivered Fast','group'=>'homepage'],
            ['key'=>'hero_subtitle','value'=>'Trade printing for businesses across the UK. Free next day delivery.','group'=>'homepage'],
            ['key'=>'hero_btn_text','value'=>'Shop All Products','group'=>'homepage'],
            ['key'=>'promo_bar_text','value'=>'Free Next Day Delivery on ALL Orders | Trade Customers Only','group'=>'homepage'],
            ['key'=>'footer_about','value'=>'London InstantPrint is a professional trade print supplier serving businesses across the UK.','group'=>'footer'],
            ['key'=>'footer_copyright','value'=>'2025 London InstantPrint. All rights reserved.','group'=>'footer'],
        ];
        foreach ($settings as $s) {
            try { if (!DB::table('site_settings')->where('key',$s['key'])->exists()) { DB::table('site_settings')->insert(array_merge($s, ['created_at'=>now(),'updated_at'=>now()])); } } catch (\Exception $e) {}
        }
    }
    public function down(): void {
        Schema::dropIfExists('page_visitors');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('admin_users');
    }
};
