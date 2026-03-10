<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add type column if missing
        if (!Schema::hasColumn('site_settings', 'type')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->string('type')->default('text')->after('value');
            });
        }

        // Add label column if missing
        if (!Schema::hasColumn('site_settings', 'label')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->string('label')->nullable()->after('type');
            });
        }

        // Seed all design settings
        $settings = [
            ['key'=>'color_primary',        'value'=>'#1e3a6e', 'type'=>'color',    'group'=>'design',   'label'=>'Primary Color (Navy)'],
            ['key'=>'color_accent',         'value'=>'#e8352a', 'type'=>'color',    'group'=>'design',   'label'=>'Accent Color (Red)'],
            ['key'=>'color_yellow',         'value'=>'#f5c518', 'type'=>'color',    'group'=>'design',   'label'=>'Highlight Color (Yellow)'],
            ['key'=>'color_bg',             'value'=>'#ffffff', 'type'=>'color',    'group'=>'design',   'label'=>'Page Background'],
            ['key'=>'color_text',           'value'=>'#222222', 'type'=>'color',    'group'=>'design',   'label'=>'Body Text Color'],
            ['key'=>'color_header_bg',      'value'=>'#ffffff', 'type'=>'color',    'group'=>'design',   'label'=>'Header Background'],
            ['key'=>'color_footer_bg',      'value'=>'#1e3a6e', 'type'=>'color',    'group'=>'design',   'label'=>'Footer Background'],
            ['key'=>'color_promo_bg',       'value'=>'#f5c518', 'type'=>'color',    'group'=>'design',   'label'=>'Promo Bar Background'],
            ['key'=>'color_promo_text',     'value'=>'#1a1a1a', 'type'=>'color',    'group'=>'design',   'label'=>'Promo Bar Text Color'],
            ['key'=>'color_hero_bg',        'value'=>'#f8f4f0', 'type'=>'color',    'group'=>'design',   'label'=>'Hero Background'],
            ['key'=>'color_hero_left',      'value'=>'#81C071', 'type'=>'color',    'group'=>'design',   'label'=>'Hero Left Panel Color'],
            ['key'=>'color_hero_right_bg',  'value'=>'#fd6c99', 'type'=>'color',    'group'=>'design',   'label'=>'Hero Right Panel Color'],
            ['key'=>'color_btn_primary_bg', 'value'=>'#1e3a6e', 'type'=>'color',    'group'=>'design',   'label'=>'Button Background'],
            ['key'=>'color_btn_primary_text','value'=>'#ffffff', 'type'=>'color',    'group'=>'design',   'label'=>'Button Text'],
            ['key'=>'color_nav_active',     'value'=>'#e8352a', 'type'=>'color',    'group'=>'design',   'label'=>'Nav Hover/Active Color'],
            ['key'=>'color_pt1',            'value'=>'linear-gradient(135deg,#b2f5b2,#1a8c1a)', 'type'=>'gradient','group'=>'design','label'=>'Product Card 1 Color'],
            ['key'=>'color_pt2',            'value'=>'linear-gradient(135deg,#ffe082,#e65100)', 'type'=>'gradient','group'=>'design','label'=>'Product Card 2 Color'],
            ['key'=>'color_pt3',            'value'=>'linear-gradient(135deg,#b3e5fc,#0277bd)', 'type'=>'gradient','group'=>'design','label'=>'Product Card 3 Color'],
            ['key'=>'color_pt4',            'value'=>'linear-gradient(135deg,#c8e6c9,#2e7d32)', 'type'=>'gradient','group'=>'design','label'=>'Product Card 4 Color'],
            // Font family hardcoded hai CSS mein via @font-face (local ABCGravity font)
            // Typography section admin mein show nahi hota
            // Font family hardcoded hai CSS mein via @font-face (local ABCGravity font)
            // Typography section admin mein show nahi hota
            // font_size_base bhi CSS mein fix hai
            ['key'=>'hero_title_line1',     'value'=>'Bound to','type'=>'text',     'group'=>'homepage', 'label'=>'Hero Title Line 1'],
            ['key'=>'hero_title_line2',     'value'=>'Impress', 'type'=>'text',     'group'=>'homepage', 'label'=>'Hero Title Line 2'],
            ['key'=>'hero_subtitle',        'value'=>'From everyday booklets to high-end brochures we have got you covered','type'=>'text','group'=>'homepage','label'=>'Hero Subtitle'],
            ['key'=>'hero_btn_text',        'value'=>'Shop Now','type'=>'text',     'group'=>'homepage', 'label'=>'Hero Button Text'],
            ['key'=>'hero_image',           'value'=>'images/hero-product.png','type'=>'image','group'=>'homepage','label'=>'Hero Product Image'],
            ['key'=>'hero_bg_color',        'value'=>'#f8f4f0', 'type'=>'color',   'group'=>'homepage', 'label'=>'Hero Background'],
            ['key'=>'trustpilot_rating',    'value'=>'4.8',     'type'=>'text',    'group'=>'homepage', 'label'=>'Trustpilot Rating'],
            ['key'=>'trustpilot_reviews',   'value'=>'10,814',  'type'=>'text',    'group'=>'homepage', 'label'=>'Trustpilot Review Count'],
            ['key'=>'promo_bar_text',       'value'=>'🎉 Free Next Day Delivery on ALL orders! Trade customers only.','type'=>'text','group'=>'general','label'=>'Promo Bar Text'],
            ['key'=>'promo_bar_enabled',    'value'=>'1',       'type'=>'toggle',  'group'=>'general',  'label'=>'Show Promo Bar'],
            ['key'=>'header_phone',         'value'=>'',        'type'=>'text',    'group'=>'header',   'label'=>'Header Phone'],
            ['key'=>'header_logo',          'value'=>'',        'type'=>'image',   'group'=>'header',   'label'=>'Site Logo'],
            ['key'=>'footer_hours',         'value'=>'Monday to Friday 9am - 5:30pm','type'=>'text','group'=>'footer','label'=>'Business Hours'],
            ['key'=>'footer_phone',         'value'=>'0114 294 5026','type'=>'text','group'=>'footer',  'label'=>'Footer Phone'],
            ['key'=>'footer_email',         'value'=>'sales@Londoninstantprint.co.uk','type'=>'text','group'=>'footer','label'=>'Footer Email'],
            ['key'=>'footer_address',       'value'=>"Unit A Brookfields Park, Manvers Way,\nManvers, Rotherham, S63 5DR",'type'=>'textarea','group'=>'footer','label'=>'Footer Address'],
            ['key'=>'footer_facebook',      'value'=>'#',       'type'=>'text',    'group'=>'footer',   'label'=>'Facebook URL'],
            ['key'=>'footer_linkedin',      'value'=>'#',       'type'=>'text',    'group'=>'footer',   'label'=>'LinkedIn URL'],
            ['key'=>'footer_twitter',       'value'=>'#',       'type'=>'text',    'group'=>'footer',   'label'=>'Twitter/X URL'],
            ['key'=>'footer_copyright',     'value'=>'London InstantPrint','type'=>'text','group'=>'footer','label'=>'Copyright Name'],
            ['key'=>'site_name',            'value'=>'London InstantPrint','type'=>'text','group'=>'seo','label'=>'Site Name'],
            ['key'=>'meta_description',     'value'=>'Professional printing services UK. Free next day delivery.','type'=>'textarea','group'=>'seo','label'=>'Default Meta Description'],
            ['key'=>'google_analytics',     'value'=>'',        'type'=>'text',    'group'=>'seo',      'label'=>'Google Analytics ID'],
        ];

        foreach ($settings as $s) {
            try {
                if (!DB::table('site_settings')->where('key', $s['key'])->exists()) {
                    DB::table('site_settings')->insert(array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
                }
            } catch (\Exception $e) {}
        }
    }

    public function down(): void {}
};
