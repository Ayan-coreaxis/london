<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('blogs')) Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->string('author')->default('London InstantPrint');
            $table->enum('status', ['published','draft'])->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed 4 default blog posts
        DB::table('blogs')->insert([
            ['title'=>'Smart Ways to Save Money on Printing Costs','slug'=>'save-money-printing','excerpt'=>'Discover insider tips to reduce your print costs without compromising on quality.','image'=>'images/Blog1.webp','category'=>'Tips','status'=>'published','sort_order'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'The Ultimate Guide to Frame Sizes for Artwork','slug'=>'frame-sizes-guide','excerpt'=>'Everything you need to know about choosing the right frame size for your artwork.','image'=>'images/Blog2.webp','category'=>'Guide','status'=>'published','sort_order'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'How to Write Wedding RSVP Cards: Wording & Design Tips','slug'=>'wedding-rsvp-cards','excerpt'=>'Get the wording, etiquette and design tips for perfect wedding RSVP cards.','image'=>'images/Blog3.webp','category'=>'Design','status'=>'published','sort_order'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'How to Design a Banner in Photoshop: Step-by-Step','slug'=>'design-banner-photoshop','excerpt'=>'A complete step-by-step guide to designing professional banners in Photoshop.','image'=>'images/Blog4.webp','category'=>'Tutorial','status'=>'published','sort_order'=>4,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // Seed site settings for home page
        $settings = [
            // Hero
            ['key'=>'hero_title_line1',    'value'=>'Bound to',         'type'=>'text',  'group'=>'homepage', 'label'=>'Hero Title Line 1'],
            ['key'=>'hero_title_line2',    'value'=>'Impress',           'type'=>'text',  'group'=>'homepage', 'label'=>'Hero Title Line 2'],
            ['key'=>'hero_subtitle',       'value'=>'From everyday booklets to high-end brochures we have got you covered', 'type'=>'text', 'group'=>'homepage', 'label'=>'Hero Subtitle'],
            ['key'=>'hero_btn_text',       'value'=>'Shop Now',          'type'=>'text',  'group'=>'homepage', 'label'=>'Hero Button Text'],
            // Promo bar
            ['key'=>'promo_bar_text',      'value'=>'🎉 Free Next Day Delivery on ALL orders! Trade customers only.', 'type'=>'text', 'group'=>'general', 'label'=>'Promo Bar Text'],
            ['key'=>'promo_bar_enabled',   'value'=>'1',                 'type'=>'toggle','group'=>'general', 'label'=>'Show Promo Bar'],
            // Trust strip
            ['key'=>'trustpilot_rating',   'value'=>'4.8',               'type'=>'text',  'group'=>'homepage', 'label'=>'Trustpilot Rating'],
            ['key'=>'trustpilot_reviews',  'value'=>'10,814',            'type'=>'text',  'group'=>'homepage', 'label'=>'Trustpilot Review Count'],
            // Contact
            ['key'=>'contact_phone',       'value'=>'+44 20 1234 5678',  'type'=>'text',  'group'=>'contact',  'label'=>'Phone Number'],
            ['key'=>'contact_email',       'value'=>'hello@londoninstantprint.co.uk','type'=>'text','group'=>'contact','label'=>'Email Address'],
            ['key'=>'contact_address',     'value'=>'London, UK',        'type'=>'text',  'group'=>'contact',  'label'=>'Address'],
            // SEO
            ['key'=>'site_name',           'value'=>'London InstantPrint','type'=>'text', 'group'=>'seo',      'label'=>'Site Name'],
            ['key'=>'meta_description',    'value'=>'Professional printing services UK. Free next day delivery. Trade customers only.','type'=>'textarea','group'=>'seo','label'=>'Default Meta Description'],
        ];
        foreach ($settings as $s) {
            $exists = DB::table('site_settings')->where('key',$s['key'])->first();
            if (!$exists) {
                try { if (!DB::table('site_settings')->where('key',$s['key'])->exists()) { DB::table('site_settings')->insert(array_merge($s, ['created_at'=>now(),'updated_at'=>now()])); } } catch (\Exception $e) {}
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
