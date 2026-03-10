<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder {
    public function run(): void {

        // ── Business Cards ──
        DB::table('products')->insert(['name'=>'Business Cards','slug'=>'business-cards','category'=>'Business Cards','description'=>"Nothing says 'let's connect' like a Business Card with personality. Our Business Card printing service helps you create custom cards that truly represent your brand. Whether you're including your contact details or a QR code, personalise your pocket-sized marketing tool and leave a lasting impression.",'base_price'=>12.99,'sku'=>'BC-001','image'=>null,'status'=>'active','created_at'=>now(),'updated_at'=>now()]);
        $bcId = DB::getPdo()->lastInsertId();

        DB::table('product_presets')->insert(['product_id'=>$bcId,'label'=>'Most Popular','description'=>'85mm x 55mm landscape cards, printed on both sides of 400gsm Silk paper, with double-sided matt lamination.','badge_color'=>'#d93025','sort_order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('product_variants')->insert(['product_id'=>$bcId,'name'=>'Folded Business Cards','link_slug'=>'folded-business-cards','sort_order'=>1,'created_at'=>now(),'updated_at'=>now()]);

        $opts = [
            ['Finished Size','dropdown',['85mm x 55mm (Square Corners)','85mm x 55mm (Round Corners)','A6','A5','A4'],[0,0,1.00,2.00,3.50]],
            ['Printed Sides','buttons', ['Single Sided','Double Sided'],[0,3.00]],
            ['Stock','dropdown',['115gsm Silk','350gsm Silk','450gsm Silk','400gsm Uncoated'],[0,2.00,4.00,1.50]],
            ['Lamination','dropdown',['No Lamination','Gloss Lamination','Matt Lamination','Soft Touch Lamination'],[0,2.50,2.50,4.00]],
            ['Embellishment','dropdown',['None','Spot UV','Foil Stamping','Embossing'],[0,5.00,8.00,7.00]],
            ['Number of Sets','dropdown',['1 Set','2 Sets','3 Sets'],[0,0,0]],
            ['Custom Quantity','buttons',['No','Yes'],[0,0]],
        ];
        foreach($opts as $s=>$o) {
            DB::table('product_options')->insert(['product_id'=>$bcId,'option_name'=>$o[0],'display_type'=>$o[1],'sort_order'=>$s+1,'created_at'=>now(),'updated_at'=>now()]);
            $oid=DB::getPdo()->lastInsertId();
            foreach($o[2] as $j=>$v) DB::table('option_values')->insert(['option_id'=>$oid,'value_label'=>$v,'extra_price'=>$o[3][$j],'sort_order'=>$j+1,'created_at'=>now(),'updated_at'=>now()]);
    
        // ===== SEED TURNAROUNDS & PRICING for first product =====
        $firstProduct = DB::selectOne("SELECT id FROM products WHERE slug='business-cards' LIMIT 1");
        if ($firstProduct) {
            $pid = $firstProduct->id;
            $turnarounds = [
                ['Express',        1, 1, '5:00pm',  [50=>14.99, 100=>19.99, 250=>29.99, 500=>44.99, 1000=>69.99]],
                ['1 Working Day',  2, 2, '6:30pm',  [50=>11.99, 100=>15.99, 250=>24.99, 500=>37.99, 1000=>59.99]],
                ['3-4 Working Days',4, 5,'6:30pm',  [50=>7.99,  100=>10.99, 250=>17.99, 500=>27.99, 1000=>44.99]],
            ];
            foreach ($turnarounds as $sort => $t) {
                DB::insert(
                    "INSERT INTO product_turnarounds (product_id,label,working_days_min,working_days_max,artwork_deadline,sort_order,created_at,updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())",
                    [$pid, $t[0], $t[1], $t[2], $t[3], $sort+1]
                );
                $tid = DB::getPdo()->lastInsertId();
                $j = 1;
                foreach ($t[4] as $qty => $price) {
                    DB::insert(
                        "INSERT INTO product_pricing (turnaround_id,quantity,price,sort_order,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())",
                        [$tid, $qty, $price, $j++]
                    );
                }
            }
        }
    }

        $faqs=[
            ['How long will my print take to arrive?','Find out how quickly you can get your print by filling in your selected options in the product builder, and check out the delivery options at the bottom.'],
            ['How do I set up my artwork for print?','Ensure your file is in CMYK colour mode, at 300 DPI resolution, and includes a 3mm bleed on all sides. We accept PDF, AI, EPS, and TIFF files.'],
            ['How to print Business Cards?','Simply select your preferred size, quantity, and finish, upload your artwork, and place your order.'],
            ['What is the standard Business Card size?','The standard UK Business Card size is 85mm x 55mm. We also offer mini (55mm x 35mm) and square formats.'],
            ['What shapes are available for Business Cards?','We offer standard rectangle, rounded corners, square, and circle shaped Business Cards.'],
            ['What is the radius on the round corners?','Our rounded corner Business Cards have a 3mm corner radius by default.'],
            ['What resolution is best for Business Card artwork?','We recommend a minimum of 300 DPI for all print artwork to ensure sharp, professional results.'],
            ['What material is best to print Business Cards?','Our 400gsm silk-coated card is our most popular option. We also offer uncoated, kraft, and luxury laminated stocks.'],
            ['Can I get custom Business Cards with embossing or debossing?','Yes! Embossing raises the design from the surface, while debossing presses it in. Both create a premium tactile effect.'],
            ['Can I print with Versions on Business Cards?','Yes, you can print different names, contact details, or designs on each card using our Versions feature.'],
        ];
        foreach($faqs as $i=>$f) DB::table('product_faqs')->insert(['product_id'=>$bcId,'question'=>$f[0],'answer'=>$f[1],'sort_order'=>$i+1,'created_at'=>now(),'updated_at'=>now()]);

        // ── Leaflets & Flyers ──
        DB::table('products')->insert(['name'=>'Leaflets & Flyers','slug'=>'leaflets-flyers','category'=>'Flyers & Leaflets','description'=>'Make a lasting impression with our high-quality leaflets and flyers. Perfect for promotions, events, and marketing campaigns.','base_price'=>8.99,'sku'=>'FL-001','image'=>null,'status'=>'active','created_at'=>now(),'updated_at'=>now()]);
        $flId=DB::getPdo()->lastInsertId();
        $flOpts=[
            ['Finished Size','dropdown',['A6','A5','A4','A3','DL'],[0,1.00,2.50,4.00,0.50]],
            ['Printed Sides','buttons',['Single Sided','Double Sided'],[0,2.50]],
            ['Stock','dropdown',['115gsm Silk','130gsm Gloss','170gsm Silk','350gsm Silk'],[0,0.50,1.00,3.00]],
            ['Lamination','dropdown',['No Lamination','Gloss Lamination','Matt Lamination'],[0,2.00,2.00]],
        ];
        foreach($flOpts as $s=>$o){
            DB::table('product_options')->insert(['product_id'=>$flId,'option_name'=>$o[0],'display_type'=>$o[1],'sort_order'=>$s+1,'created_at'=>now(),'updated_at'=>now()]);
            $oid=DB::getPdo()->lastInsertId();
            foreach($o[2] as $j=>$v) DB::table('option_values')->insert(['option_id'=>$oid,'value_label'=>$v,'extra_price'=>$o[3][$j],'sort_order'=>$j+1,'created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Postcards ──
        DB::table('products')->insert(['name'=>'Postcards','slug'=>'postcards','category'=>'Business Cards','description'=>'Send a personal touch with our premium postcards. Ideal for direct mail campaigns, thank you notes, and promotional giveaways.','base_price'=>9.99,'sku'=>'PC-001','image'=>null,'status'=>'active','created_at'=>now(),'updated_at'=>now()]);

        // ── Banners ──
        DB::table('products')->insert(['name'=>'Banners','slug'=>'banners','category'=>'Banners','description'=>'Eye-catching banners for events, exhibitions, and retail. Printed on durable vinyl with hem and eyelets for easy hanging.','base_price'=>24.99,'sku'=>'BN-001','image'=>null,'status'=>'active','created_at'=>now(),'updated_at'=>now()]);
    }
}
    // This is appended — run seedTurnarounds() manually or add to run()