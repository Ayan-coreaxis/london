<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $settings = [
            // ── ABOUT US ──
            ['key'=>'about_hero_title',      'value'=>'About London InstantPrint',   'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Hero Title'],
            ['key'=>'about_hero_subtitle',   'value'=>'Your trusted trade print partner since 2010. We deliver quality printing with free next-day delivery across the UK.', 'type'=>'textarea', 'group'=>'pages', 'label'=>'About Us — Hero Subtitle'],
            ['key'=>'about_story_title',     'value'=>'Our Story',                   'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Story Heading'],
            ['key'=>'about_story_body',      'value'=>"London InstantPrint was founded with one simple goal: give trade professionals fast, affordable, high-quality print.\n\nOver the years we've grown from a small workshop to one of the UK's leading trade print suppliers — but our values haven't changed. Every order is checked by our 30-point artwork team, packed carefully, and dispatched for free next-day delivery.\n\nWe serve printers, designers, agencies, and marketing teams who need reliable turnaround times and consistent quality they can stake their own reputation on.", 'type'=>'textarea', 'group'=>'pages', 'label'=>'About Us — Story Body'],
            ['key'=>'about_mission_title',   'value'=>'Our Mission',                 'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Mission Heading'],
            ['key'=>'about_mission_body',    'value'=>'To be the most reliable trade print partner in the UK — combining cutting-edge print technology with friendly, expert service and unbeatable delivery speeds.', 'type'=>'textarea', 'group'=>'pages', 'label'=>'About Us — Mission Body'],
            ['key'=>'about_stat1_number',    'value'=>'50,000+',                     'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 1 Number'],
            ['key'=>'about_stat1_label',     'value'=>'Orders Delivered',            'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 1 Label'],
            ['key'=>'about_stat2_number',    'value'=>'10,000+',                     'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 2 Number'],
            ['key'=>'about_stat2_label',     'value'=>'Happy Customers',             'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 2 Label'],
            ['key'=>'about_stat3_number',    'value'=>'15+',                         'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 3 Number'],
            ['key'=>'about_stat3_label',     'value'=>'Years in Business',           'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 3 Label'],
            ['key'=>'about_stat4_number',    'value'=>'4.8★',                        'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 4 Number'],
            ['key'=>'about_stat4_label',     'value'=>'Trustpilot Rating',           'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Stat 4 Label'],
            ['key'=>'about_team_title',      'value'=>'Meet Our Team',               'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Team Section Title'],
            ['key'=>'about_team_subtitle',   'value'=>'Dedicated professionals who make every order happen.',  'type'=>'text', 'group'=>'pages', 'label'=>'About Us — Team Subtitle'],
            ['key'=>'about_values_title',    'value'=>'What We Stand For',           'type'=>'text',     'group'=>'pages', 'label'=>'About Us — Values Title'],
            ['key'=>'about_cta_title',       'value'=>'Ready to place your first order?', 'type'=>'text','group'=>'pages', 'label'=>'About Us — CTA Title'],
            ['key'=>'about_cta_body',        'value'=>'Browse our full range of trade print products and enjoy free next-day delivery on every order.', 'type'=>'textarea', 'group'=>'pages', 'label'=>'About Us — CTA Body'],

            // ── PRIVACY POLICY ──
            ['key'=>'privacy_last_updated',  'value'=>'1 March 2025',               'type'=>'text',     'group'=>'pages', 'label'=>'Privacy Policy — Last Updated Date'],
            ['key'=>'privacy_company_name',  'value'=>'London InstantPrint Ltd',     'type'=>'text',     'group'=>'pages', 'label'=>'Privacy Policy — Company Name'],
            ['key'=>'privacy_company_address','value'=>'Unit A Brookfields Park, Manvers Way, Manvers, Rotherham, S63 5DR', 'type'=>'textarea', 'group'=>'pages', 'label'=>'Privacy Policy — Company Address'],
            ['key'=>'privacy_contact_email', 'value'=>'privacy@londoninstantprint.co.uk', 'type'=>'text','group'=>'pages', 'label'=>'Privacy Policy — Contact Email'],
            ['key'=>'privacy_intro',         'value'=>'We are committed to protecting your personal information and your right to privacy. This policy explains what information we collect, how we use it, and what rights you have in relation to it.', 'type'=>'textarea', 'group'=>'pages', 'label'=>'Privacy Policy — Intro Paragraph'],

            // ── TERMS & CONDITIONS ──
            ['key'=>'terms_last_updated',    'value'=>'1 March 2025',               'type'=>'text',     'group'=>'pages', 'label'=>'Terms & Conditions — Last Updated Date'],
            ['key'=>'terms_company_name',    'value'=>'London InstantPrint Ltd',     'type'=>'text',     'group'=>'pages', 'label'=>'Terms & Conditions — Company Name'],
            ['key'=>'terms_intro',           'value'=>'Please read these terms and conditions carefully before using our website or placing an order. By accessing our site or placing an order, you agree to be bound by these terms.', 'type'=>'textarea', 'group'=>'pages', 'label'=>'Terms & Conditions — Intro'],
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
