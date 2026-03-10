<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VariationController extends Controller
{
    private function uploadImage($file): string
    {
        $folder = public_path('uploads/products');
        if (!file_exists($folder)) mkdir($folder, 0755, true);
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);
        return 'uploads/products/' . $filename;
    }

    private function uniqueSlug(string $s): string
    {
        $o = $s; $i = 1;
        while (DB::selectOne("SELECT id FROM products WHERE slug=?", [$s])) $s = $o . '-' . $i++;
        return $s;
    }

    // ═══ PRODUCT LIST ═══
    public function index()
    {
        $products = DB::select("
            SELECT p.*,
                   (SELECT COUNT(*) FROM product_attributes WHERE product_id = p.id) as attr_count,
                   (SELECT COUNT(*) FROM product_variations WHERE product_id = p.id) as var_count
            FROM products p ORDER BY p.created_at DESC
        ");
        return view('admin.variation-products.index', compact('products'));
    }

    // ═══ CREATE PRODUCT ═══
    public function create()
    {
        return view('admin.variation-products.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $images = [];
        for ($i = 1; $i <= 4; $i++) {
            $images["image$i"] = $request->hasFile("image$i") ? $this->uploadImage($request->file("image$i")) : null;
        }
        $slug = $this->uniqueSlug(Str::slug($request->name));
        DB::insert("INSERT INTO products (name,slug,category,description,base_price,sku,image1,image2,image3,image4,status,sort_order,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())",
            [$request->name, $slug, $request->category ?? '', $request->description ?? '', $request->base_price ?? 0, $request->sku ?? '', $images['image1'], $images['image2'], $images['image3'], $images['image4'], $request->status ?? 'active', $request->sort_order ?? 0]);
        $pid = DB::getPdo()->lastInsertId();
        return redirect()->route('admin.vpm.edit', $pid)->with('success', "Product '{$request->name}' created! Now set up variations.");
    }

    // ═══ EDIT PRODUCT (Variation Manager page) ═══
    public function edit(int $id)
    {
        $product = DB::selectOne("SELECT * FROM products WHERE id = ?", [$id]);
        if (!$product) abort(404);
        return view('admin.variation-products.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $imgSql = []; $imgParams = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image$i")) { $imgSql[] = "image$i=?"; $imgParams[] = $this->uploadImage($request->file("image$i")); }
        }
        $imgStr = count($imgSql) ? ',' . implode(',', $imgSql) : '';
        DB::update("UPDATE products SET name=?,category=?,description=?,base_price=?,sku=?,status=?,sort_order=? $imgStr,updated_at=NOW() WHERE id=?",
            array_merge([$request->name, $request->category ?? '', $request->description ?? '', $request->base_price ?? 0, $request->sku ?? '', $request->status ?? 'active', $request->sort_order ?? 0], $imgParams, [$id]));
        return redirect()->route('admin.vpm.edit', $id)->with('success', 'Product updated!');
    }

    public function destroy(int $id)
    {
        DB::delete("DELETE FROM products WHERE id=?", [$id]);
        return redirect()->route('admin.vpm.index')->with('success', 'Product deleted.');
    }

    // ═══ VARIATION DATA API (GET) ═══
    public function getData(int $productId)
    {
        $attributes = DB::select("SELECT pa.id, pa.name, pa.visible, pa.used_for_variations, pa.sort_order FROM product_attributes pa WHERE pa.product_id = ? ORDER BY pa.sort_order", [$productId]);
        foreach ($attributes as &$attr) {
            $attr->values = DB::select("SELECT id, value, sort_order FROM product_attribute_values WHERE attribute_id = ? ORDER BY sort_order", [$attr->id]);
            $attr->visible = (bool)$attr->visible;
            $attr->used_for_variations = (bool)$attr->used_for_variations;
        }
        $turnarounds = DB::select("SELECT id, label, working_days_min, working_days_max, artwork_deadline, sort_order FROM product_turnarounds WHERE product_id = ? ORDER BY sort_order", [$productId]);
        $quantities = DB::select("SELECT id, quantity, sort_order FROM product_quantities WHERE product_id = ? ORDER BY sort_order", [$productId]);
        $variations = DB::select("SELECT id, sku, enabled, sort_order FROM product_variations WHERE product_id = ? ORDER BY sort_order", [$productId]);
        foreach ($variations as &$var) {
            $var->enabled = (bool)$var->enabled;
            $var->selections = DB::select("SELECT pva.attribute_id, pva.attribute_value_id FROM product_variation_attributes pva WHERE pva.variation_id = ?", [$var->id]);
            $var->pricing = DB::select("SELECT turnaround_id, quantity, price, disabled FROM product_variation_pricing WHERE variation_id = ?", [$var->id]);
            foreach ($var->pricing as &$p) { $p->price = (float)$p->price; $p->disabled = (bool)$p->disabled; }
            $var->disabled_quantities = array_map(fn($r) => (int)$r->quantity, DB::select("SELECT quantity FROM product_variation_disabled_qty WHERE variation_id = ?", [$var->id]));
        }
        return response()->json(['attributes' => $attributes, 'turnarounds' => $turnarounds, 'quantities' => $quantities, 'variations' => $variations]);
    }

    // ═══ VARIATION DATA API (SAVE) ═══
    public function saveData(Request $request, int $productId)
    {
        DB::beginTransaction();
        try {
            DB::delete("DELETE FROM product_attributes WHERE product_id = ?", [$productId]);
            $attrIdMap = []; $valueIdMap = [];
            foreach ($request->input('attributes', []) as $i => $attr) {
                DB::insert("INSERT INTO product_attributes (product_id, name, visible, used_for_variations, sort_order, created_at, updated_at) VALUES (?,?,?,?,?,NOW(),NOW())", [$productId, $attr['name'], $attr['visible'] ? 1 : 0, $attr['used_for_variations'] ? 1 : 0, $i]);
                $newAttrId = DB::getPdo()->lastInsertId();
                $attrIdMap[$attr['id']] = $newAttrId;
                foreach (($attr['values'] ?? []) as $j => $val) {
                    DB::insert("INSERT INTO product_attribute_values (attribute_id, value, sort_order, created_at, updated_at) VALUES (?,?,?,NOW(),NOW())", [$newAttrId, $val['value'], $j]);
                    $valueIdMap[$val['id']] = DB::getPdo()->lastInsertId();
                }
            }
            DB::delete("DELETE FROM product_quantities WHERE product_id = ?", [$productId]);
            foreach ($request->input('quantities', []) as $i => $q) {
                DB::insert("INSERT INTO product_quantities (product_id, quantity, sort_order, created_at, updated_at) VALUES (?,?,?,NOW(),NOW())", [$productId, (int)$q['quantity'], $i]);
            }
            DB::delete("DELETE FROM product_turnarounds WHERE product_id = ?", [$productId]);
            $turnIdMap = [];
            foreach ($request->input('turnarounds', []) as $i => $t) {
                DB::insert("INSERT INTO product_turnarounds (product_id, label, working_days_min, working_days_max, artwork_deadline, sort_order, created_at, updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())", [$productId, $t['label'], (int)($t['working_days_min'] ?? 1), (int)($t['working_days_max'] ?? 1), $t['artwork_deadline'] ?? '', $i]);
                $turnIdMap[$t['id']] = DB::getPdo()->lastInsertId();
            }
            DB::delete("DELETE FROM product_variations WHERE product_id = ?", [$productId]);
            foreach ($request->input('variations', []) as $i => $var) {
                DB::insert("INSERT INTO product_variations (product_id, sku, enabled, sort_order, created_at, updated_at) VALUES (?,?,?,?,NOW(),NOW())", [$productId, $var['sku'] ?? '', ($var['enabled'] ?? true) ? 1 : 0, $i]);
                $newVarId = DB::getPdo()->lastInsertId();
                foreach (($var['selections'] ?? []) as $sel) {
                    $ma = $attrIdMap[$sel['attribute_id']] ?? null; $mv = $valueIdMap[$sel['attribute_value_id']] ?? null;
                    if ($ma && $mv) DB::insert("INSERT INTO product_variation_attributes (variation_id, attribute_id, attribute_value_id, created_at, updated_at) VALUES (?,?,?,NOW(),NOW())", [$newVarId, $ma, $mv]);
                }
                foreach (($var['pricing'] ?? []) as $p) {
                    $mt = $turnIdMap[$p['turnaround_id']] ?? null;
                    if ($mt) DB::insert("INSERT INTO product_variation_pricing (variation_id, turnaround_id, quantity, price, disabled, created_at, updated_at) VALUES (?,?,?,?,?,NOW(),NOW())", [$newVarId, $mt, (int)$p['quantity'], (float)($p['price'] ?? 0), ($p['disabled'] ?? false) ? 1 : 0]);
                }
                foreach (($var['disabled_quantities'] ?? []) as $dq) {
                    DB::insert("INSERT INTO product_variation_disabled_qty (variation_id, quantity, created_at, updated_at) VALUES (?,?,NOW(),NOW())", [$newVarId, (int)$dq]);
                }
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function frontendData(int $productId) { return $this->getData($productId); }
}
