<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // =============================================
    // Helper: Image ko public/uploads/products/ mein save karo
    // =============================================
    private function uploadImage($file): string
    {
        $folder = public_path('uploads/products');

        // Folder na ho to bana do
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);

        return 'uploads/products/' . $filename;
    }

    public function show(string $slug)
    {
        $product = DB::selectOne("SELECT * FROM products WHERE slug=? AND status='active' LIMIT 1", [$slug]);
        if (!$product) abort(404);
        $id = $product->id;

        $rows = DB::select("SELECT po.id AS oid, po.option_name, po.display_type, ov.id AS vid, ov.value_label, ov.extra_price FROM product_options po LEFT JOIN option_values ov ON ov.option_id=po.id WHERE po.product_id=? ORDER BY po.sort_order, ov.sort_order", [$id]);
        $options = [];
        foreach ($rows as $r) {
            if (!isset($options[$r->oid])) $options[$r->oid] = ['id'=>$r->oid,'option_name'=>$r->option_name,'display_type'=>$r->display_type,'values'=>[]];
            if ($r->vid) $options[$r->oid]['values'][] = ['id'=>$r->vid,'value_label'=>$r->value_label,'extra_price'=>(float)$r->extra_price];
        }

        $presets  = DB::select("SELECT * FROM product_presets  WHERE product_id=? ORDER BY sort_order", [$id]);
        $variants = DB::select("SELECT * FROM product_variants WHERE product_id=? ORDER BY sort_order", [$id]);
        $faqRows  = DB::select("SELECT * FROM product_faqs     WHERE product_id=? ORDER BY sort_order", [$id]);
        $faqs     = array_map(fn($f) => ['q'=>$f->question,'a'=>$f->answer], $faqRows);

        $related = DB::select("SELECT id,name,slug,image1 FROM products WHERE category=? AND id!=? AND status='active' LIMIT 4", [$product->category, $id]);
        if (empty($related)) $related = DB::select("SELECT id,name,slug,image1 FROM products WHERE id!=? AND status='active' LIMIT 4", [$id]);

        $colors = ['bg-teal','bg-yellow','bg-navy','bg-green'];
        $relatedProducts = [];
        foreach ($related as $i => $rp) {
            $relatedProducts[] = ['name'=>$rp->name,'image'=>$rp->image1,'url'=>route('product.show',$rp->slug),'thumb_class'=>$colors[$i%4]];
        }

        // Load turnaround pricing (graceful fallback if table doesn't exist yet)
        $turnarounds = [];
        try {
            $turnaroundRows = DB::select(
                "SELECT pt.id, pt.label, pt.working_days_min, pt.working_days_max, pt.artwork_deadline,
                        pp.quantity, pp.price
                 FROM product_turnarounds pt
                 LEFT JOIN product_pricing pp ON pp.turnaround_id = pt.id
                 WHERE pt.product_id = ?
                 ORDER BY pt.sort_order, pp.sort_order",
                [$id]
            );
            foreach ($turnaroundRows as $tr) {
                if (!isset($turnarounds[$tr->id])) {
                    $turnarounds[$tr->id] = [
                        'id'               => $tr->id,
                        'label'            => $tr->label,
                        'working_days_min' => (int)$tr->working_days_min,
                        'working_days_max' => (int)$tr->working_days_max,
                        'artwork_deadline' => $tr->artwork_deadline,
                        'pricing'          => [],
                    ];
                }
                if ($tr->quantity) {
                    $turnarounds[$tr->id]['pricing'][] = [
                        'quantity' => (int)$tr->quantity,
                        'price'    => (float)$tr->price,
                    ];
                }
            }
        } catch (\Exception $e) {
            $turnarounds = [];
        }
        $turnarounds = array_values($turnarounds);

        // ===== VARIATION-BASED PRICING =====
        $variationData = ['attributes' => [], 'turnarounds_v' => [], 'quantities' => [], 'variations' => []];
        try {
            $vAttrs = DB::select("SELECT id, name, visible, used_for_variations, sort_order FROM product_attributes WHERE product_id = ? ORDER BY sort_order", [$id]);
            foreach ($vAttrs as &$va) {
                $va->values = DB::select("SELECT id, value, sort_order FROM product_attribute_values WHERE attribute_id = ? ORDER BY sort_order", [$va->id]);
                $va->visible = (bool)$va->visible;
                $va->used_for_variations = (bool)$va->used_for_variations;
            }
            $variationData['attributes'] = $vAttrs;
            $variationData['turnarounds_v'] = DB::select("SELECT id, label, working_days_min, working_days_max, artwork_deadline, sort_order FROM product_turnarounds WHERE product_id = ? ORDER BY sort_order", [$id]);
            $variationData['quantities'] = DB::select("SELECT id, quantity, sort_order FROM product_quantities WHERE product_id = ? ORDER BY sort_order", [$id]);
            $vVars = DB::select("SELECT id, sku, enabled, sort_order FROM product_variations WHERE product_id = ? ORDER BY sort_order", [$id]);
            foreach ($vVars as &$vv) {
                $vv->enabled = (bool)$vv->enabled;
                $vv->selections = DB::select("SELECT attribute_id, attribute_value_id FROM product_variation_attributes WHERE variation_id = ?", [$vv->id]);
                $vv->pricing = DB::select("SELECT turnaround_id, quantity, price, disabled FROM product_variation_pricing WHERE variation_id = ?", [$vv->id]);
                foreach ($vv->pricing as &$pp) { $pp->price = (float)$pp->price; $pp->disabled = (bool)$pp->disabled; }
                $vv->disabled_quantities = array_map(fn($r) => (int)$r->quantity, DB::select("SELECT quantity FROM product_variation_disabled_qty WHERE variation_id = ?", [$vv->id]));
            }
            $variationData['variations'] = $vVars;
        } catch (\Exception $e) {}
        $hasVariations = count($variationData['variations']) > 0 && count($variationData['attributes']) > 0;

        return view('pages.product-details', [
            'product'         => (array)$product,
            'options'         => array_values($options),
            'presets'         => $presets,
            'variants'        => $variants,
            'faqs'            => $faqs,
            'relatedProducts' => $relatedProducts,
            'turnarounds'     => $turnarounds,
            'variationData'   => $variationData,
            'hasVariations'   => $hasVariations,
        ]);
    }

    public function adminIndex()
    {
        $products = DB::select("SELECT * FROM products ORDER BY created_at DESC");
        return view('admin.products.index', compact('products'));
    }

    public function adminCreate()
    {
        return view('admin.products.form', ['isEdit'=>false,'product'=>null,'optionsRaw'=>[],'presets'=>[],'variants'=>[],'faqs'=>[]]);
    }

    public function adminStore(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255']);

        // ✅ FIX: Images public/uploads/products/ mein save ho rahi hain
        $images = [];
        for ($i = 1; $i <= 4; $i++) {
            $images["image$i"] = $request->hasFile("image$i")
                ? $this->uploadImage($request->file("image$i"))
                : null;
        }

        $slug = $this->uniqueSlug(Str::slug($request->name));
        try {
            DB::insert("INSERT INTO products (name,slug,category,description,base_price,sku,image1,image2,image3,image4,status,sort_order,artwork_setup_text,artwork_templates_text,technical_spec_text,key_info_text,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())",
                [$request->name,$slug,$request->category??'',$request->description??'',$request->base_price??0,$request->sku??'',$images['image1'],$images['image2'],$images['image3'],$images['image4'],$request->status??'active',$request->sort_order??0,$request->artwork_setup_text,$request->artwork_templates_text,$request->technical_spec_text,$request->key_info_text]);
        } catch (\Exception $e) {
            // Fallback if new columns don't exist yet
            DB::insert("INSERT INTO products (name,slug,category,description,base_price,sku,image1,image2,image3,image4,status,sort_order,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())",
                [$request->name,$slug,$request->category??'',$request->description??'',$request->base_price??0,$request->sku??'',$images['image1'],$images['image2'],$images['image3'],$images['image4'],$request->status??'active',$request->sort_order??0]);
        }
        $pid = DB::getPdo()->lastInsertId();
        $this->saveOptions($pid,$request); $this->savePresets($pid,$request);
        $this->saveVariants($pid,$request); $this->saveFaqs($pid,$request);
        try { \App\Helpers\AdminLog::log('created_product', 'product', $pid, "Created: {$request->name}"); } catch (\Exception $e) {}
        return redirect()->route('admin.products.index')->with('success',"Product '{$request->name}' added!");
    }

    public function adminEdit(int $id)
    {
        $product = DB::selectOne("SELECT * FROM products WHERE id=?",[$id]);
        if (!$product) abort(404);
        $optionsRaw = DB::select("SELECT po.id,po.option_name,po.display_type, GROUP_CONCAT(ov.value_label ORDER BY ov.sort_order SEPARATOR '\n') AS values_str, GROUP_CONCAT(ov.extra_price ORDER BY ov.sort_order SEPARATOR '\n') AS prices_str FROM product_options po LEFT JOIN option_values ov ON ov.option_id=po.id WHERE po.product_id=? GROUP BY po.id, po.option_name, po.display_type, po.sort_order ORDER BY po.sort_order",[$id]);
        $presets  = DB::select("SELECT * FROM product_presets  WHERE product_id=? ORDER BY sort_order",[$id]);
        $variants = DB::select("SELECT * FROM product_variants WHERE product_id=? ORDER BY sort_order",[$id]);
        $faqs     = DB::select("SELECT * FROM product_faqs     WHERE product_id=? ORDER BY sort_order",[$id]);
        return view('admin.products.form', compact('product','optionsRaw','presets','variants','faqs')+['isEdit'=>true]);
    }

    public function adminUpdate(Request $request, int $id)
    {
        $request->validate(['name'=>'required|string|max:255']);

        // ✅ FIX: Images public/uploads/products/ mein save ho rahi hain
        $imgSql = []; $imgParams = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image$i")) {
                $imgSql[] = "image$i=?";
                $imgParams[] = $this->uploadImage($request->file("image$i"));
            }
        }

        $imgStr = count($imgSql) ? ','.implode(',',$imgSql) : '';
        try {
            DB::update("UPDATE products SET name=?,category=?,description=?,base_price=?,sku=?,status=?,sort_order=?,artwork_setup_text=?,artwork_templates_text=?,technical_spec_text=?,key_info_text=? $imgStr,updated_at=NOW() WHERE id=?",
                array_merge([$request->name,$request->category??'',$request->description??'',$request->base_price??0,$request->sku??'',$request->status??'active',$request->sort_order??0,$request->artwork_setup_text,$request->artwork_templates_text,$request->technical_spec_text,$request->key_info_text],$imgParams,[$id]));
        } catch (\Exception $e) {
            DB::update("UPDATE products SET name=?,category=?,description=?,base_price=?,sku=?,status=?,sort_order=? $imgStr,updated_at=NOW() WHERE id=?",
                array_merge([$request->name,$request->category??'',$request->description??'',$request->base_price??0,$request->sku??'',$request->status??'active',$request->sort_order??0],$imgParams,[$id]));
        }
        DB::delete("DELETE FROM product_options WHERE product_id=?",[$id]); $this->saveOptions($id,$request);
        DB::delete("DELETE FROM product_presets  WHERE product_id=?",[$id]); $this->savePresets($id,$request);
        DB::delete("DELETE FROM product_variants WHERE product_id=?",[$id]); $this->saveVariants($id,$request);
        DB::delete("DELETE FROM product_faqs     WHERE product_id=?",[$id]); $this->saveFaqs($id,$request);
        try { \App\Helpers\AdminLog::log('updated_product', 'product', $id, "Updated: {$request->name}"); } catch (\Exception $e) {}
        return redirect()->route('admin.products.edit',$id)->with('success','Product updated!');
    }

    public function adminDestroy(int $id)
    {
        try { \App\Helpers\AdminLog::log('deleted_product', 'product', $id, 'Product deleted'); } catch (\Exception $e) {}
        DB::delete("DELETE FROM products WHERE id=?",[$id]);
        return redirect()->route('admin.products.index')->with('success','Deleted.');
    }

    private function uniqueSlug(string $s): string {
        $o=$s; $i=1;
        while(DB::selectOne("SELECT id FROM products WHERE slug=?",[$s])) $s=$o.'-'.$i++;
        return $s;
    }
    private function saveOptions(int $pid, Request $r): void {
        foreach(($r->option_name??[]) as $i=>$n) {
            if(!trim($n)) continue;
            DB::insert("INSERT INTO product_options (product_id,option_name,display_type,sort_order,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())",[$pid,trim($n),$r->display_type[$i]??'dropdown',$i+1]);
            $oid=DB::getPdo()->lastInsertId();
            $vals=array_values(array_filter(array_map('trim',explode("\n",$r->option_values[$i]??''))));
            $prices=array_values(array_map('trim',explode("\n",$r->option_prices[$i]??'')));
            foreach($vals as $j=>$v) { if(!$v) continue; DB::insert("INSERT INTO option_values (option_id,value_label,extra_price,sort_order,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())",[$oid,$v,(float)($prices[$j]??0),$j+1]); }
        }
    }
    private function savePresets(int $pid, Request $r): void {
        foreach(($r->preset_label??[]) as $i=>$l) { if(!trim($l)) continue; DB::insert("INSERT INTO product_presets (product_id,label,description,badge_color,sort_order,created_at,updated_at) VALUES (?,?,?,?,?,NOW(),NOW())",[$pid,trim($l),$r->preset_description[$i]??'',$r->preset_color[$i]??'#d93025',$i+1]); }
    }
    private function saveVariants(int $pid, Request $r): void {
        foreach(($r->variant_name??[]) as $i=>$n) { if(!trim($n)) continue; DB::insert("INSERT INTO product_variants (product_id,name,link_slug,sort_order,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())",[$pid,trim($n),$r->variant_slug[$i]??'',$i+1]); }
    }
    private function saveFaqs(int $pid, Request $r): void {
        foreach(($r->faq_question??[]) as $i=>$q) { if(!trim($q)) continue; DB::insert("INSERT INTO product_faqs (product_id,question,answer,sort_order,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())",[$pid,trim($q),$r->faq_answer[$i]??'',$i+1]); }
    }

    // ===== PRICING / TURNAROUND METHODS =====

    public function adminPricingGet(int $productId)
    {
        $turnarounds = DB::select(
            "SELECT pt.*, GROUP_CONCAT(pp.quantity ORDER BY pp.sort_order SEPARATOR ',') AS quantities,
                    GROUP_CONCAT(pp.price ORDER BY pp.sort_order SEPARATOR ',') AS prices
             FROM product_turnarounds pt
             LEFT JOIN product_pricing pp ON pp.turnaround_id = pt.id
             WHERE pt.product_id = ?
             GROUP BY pt.id, pt.label, pt.working_days_min, pt.working_days_max, pt.artwork_deadline, pt.sort_order, pt.product_id, pt.created_at, pt.updated_at
             ORDER BY pt.sort_order",
            [$productId]
        );
        return response()->json($turnarounds);
    }

    public function adminPricingSave(Request $request, int $productId)
    {
        DB::delete("DELETE FROM product_turnarounds WHERE product_id = ?", [$productId]);

        $labels    = $request->input('turnaround_label', []);
        $wdMin     = $request->input('working_days_min', []);
        $wdMax     = $request->input('working_days_max', []);
        $deadlines = $request->input('artwork_deadline', []);
        $qtys      = $request->input('quantities', []);
        $prices    = $request->input('prices', []);

        foreach ($labels as $i => $label) {
            if (!trim($label)) continue;
            DB::insert(
                "INSERT INTO product_turnarounds (product_id, label, working_days_min, working_days_max, artwork_deadline, sort_order, created_at, updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())",
                [$productId, trim($label), (int)($wdMin[$i] ?? 1), (int)($wdMax[$i] ?? 1), $deadlines[$i] ?? '6:30pm', $i + 1]
            );
            $tid = DB::getPdo()->lastInsertId();

            $qList = array_filter(array_map('trim', explode(',', $qtys[$i] ?? '')));
            $pList = array_map('trim', explode(',', $prices[$i] ?? ''));

            foreach (array_values($qList) as $j => $qty) {
                if (!is_numeric($qty)) continue;
                DB::insert(
                    "INSERT INTO product_pricing (turnaround_id, quantity, price, sort_order, created_at, updated_at) VALUES (?,?,?,?,NOW(),NOW())",
                    [$tid, (int)$qty, (float)($pList[$j] ?? 0), $j + 1]
                );
            }
        }

        return response()->json(['success' => true]);
    }
}