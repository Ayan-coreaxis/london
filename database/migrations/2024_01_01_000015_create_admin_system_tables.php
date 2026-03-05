<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('email');
                $table->string('company')->nullable()->after('phone');
            });
        }

        if (!Schema::hasTable('admins')) Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['superadmin','admin','manager'])->default('admin');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        if (!Schema::hasTable('site_settings')) Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('visitors')) Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page')->nullable();
            $table->string('referer')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('visited_date');
            $table->timestamps();
        });

        if (!Schema::hasTable('order_status_history')) Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
        });

        try { DB::table('admins')->insert([
            'name'       => 'Super Admin',
            'email'      => 'admin@londoninstantprint.co.uk',
            'password'   => Hash::make('admin123'),
            'role'       => 'superadmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]); } catch (\Exception $e) {}

        $settings = [
            ['key'=>'site_name','value'=>'London InstantPrint','type'=>'text','group'=>'general','label'=>'Site Name'],
            ['key'=>'site_tagline','value'=>'Professional Printing Services','type'=>'text','group'=>'general','label'=>'Tagline'],
            ['key'=>'contact_email','value'=>'info@londoninstantprint.co.uk','type'=>'text','group'=>'general','label'=>'Contact Email'],
            ['key'=>'contact_phone','value'=>'+44 20 1234 5678','type'=>'text','group'=>'general','label'=>'Phone Number'],
            ['key'=>'contact_address','value'=>'123 Print Street, London, EC1A 1BB','type'=>'textarea','group'=>'general','label'=>'Address'],
            ['key'=>'hero_title','value'=>'Professional Printing for Trade','type'=>'text','group'=>'homepage','label'=>'Hero Title'],
            ['key'=>'hero_subtitle','value'=>'Free next day delivery on all orders','type'=>'text','group'=>'homepage','label'=>'Hero Subtitle'],
            ['key'=>'hero_btn_text','value'=>'Shop All Products','type'=>'text','group'=>'homepage','label'=>'Hero Button Text'],
            ['key'=>'promo_bar_text','value'=>'Free Next Day Delivery on All Orders!','type'=>'text','group'=>'homepage','label'=>'Promo Bar Text'],
            ['key'=>'promo_bar_enabled','value'=>'1','type'=>'boolean','group'=>'homepage','label'=>'Promo Bar Enabled'],
            ['key'=>'meta_description','value'=>'London InstantPrint - Professional printing services UK.','type'=>'textarea','group'=>'seo','label'=>'Default Meta Description'],
            ['key'=>'google_analytics','value'=>'','type'=>'text','group'=>'seo','label'=>'Google Analytics ID'],
        ];
        foreach ($settings as $s) {
            try {
                if (!DB::table('site_settings')->where('key',$s['key'])->exists()) {
                    DB::table('site_settings')->insert(array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
                }
            } catch (\Exception $e) {}
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('admins');
    }
};
