<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteManagerController extends Controller
{
    // ═══════════════════════════════════════
    // DELIVERY ZONES
    // ═══════════════════════════════════════
    public function deliveryIndex()
    {
        $zones = collect([]);
        $methods = collect([]);
        try {
            $zones = DB::table('delivery_zones')->orderBy('sort_order')->get();
            $methods = DB::table('delivery_methods')
                ->leftJoin('delivery_zones', 'delivery_methods.zone_id', '=', 'delivery_zones.id')
                ->select('delivery_methods.*', 'delivery_zones.name as zone_name')
                ->orderBy('delivery_methods.sort_order')->get();
        } catch (\Exception $e) {}
        return view('admin.delivery.index', compact('zones', 'methods'));
    }

    public function deliverySave(Request $request)
    {
        try {
        // Save zones
        $zoneIds = $request->input('zone_id', []);
        $zoneNames = $request->input('zone_name', []);
        $zonePostcodes = $request->input('zone_postcodes', []);
        $zoneActive = $request->input('zone_active', []);

        foreach ($zoneIds as $i => $id) {
            if (!trim($zoneNames[$i] ?? '')) continue;
            $data = [
                'name' => trim($zoneNames[$i]),
                'postcodes' => trim($zonePostcodes[$i] ?? ''),
                'is_active' => in_array($id, $zoneActive),
                'sort_order' => $i + 1,
                'updated_at' => now(),
            ];
            if ($id && $id !== 'new') {
                DB::table('delivery_zones')->where('id', $id)->update($data);
            } else {
                $data['created_at'] = now();
                $data['country'] = 'GB';
                DB::table('delivery_zones')->insert($data);
            }
        }

        // Save methods
        $mIds = $request->input('method_id', []);
        $mNames = $request->input('method_name', []);
        $mSlugs = $request->input('method_slug', []);
        $mDescriptions = $request->input('method_description', []);
        $mPrices = $request->input('method_price', []);
        $mDays = $request->input('method_days', []);
        $mZones = $request->input('method_zone_id', []);
        $mActive = $request->input('method_active', []);

        foreach ($mIds as $i => $id) {
            if (!trim($mNames[$i] ?? '')) continue;
            $data = [
                'name' => trim($mNames[$i]),
                'slug' => trim($mSlugs[$i] ?? '') ?: \Illuminate\Support\Str::slug($mNames[$i]),
                'description' => trim($mDescriptions[$i] ?? ''),
                'price' => (float)($mPrices[$i] ?? 0),
                'estimated_days' => trim($mDays[$i] ?? ''),
                'zone_id' => ($mZones[$i] ?? '') ?: null,
                'is_free' => (float)($mPrices[$i] ?? 0) == 0,
                'is_active' => in_array($id, $mActive),
                'sort_order' => $i + 1,
                'updated_at' => now(),
            ];
            if ($id && $id !== 'new') {
                DB::table('delivery_methods')->where('id', $id)->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('delivery_methods')->insert($data);
            }
        }

        return back()->with('success', 'Delivery settings saved!');
        } catch (\Exception $e) { return back()->with('error', 'Error: '.$e->getMessage()); }
    }

    public function deliveryDeleteZone(int $id) {
        DB::table('delivery_zones')->where('id', $id)->delete();
        return back()->with('success', 'Zone deleted.');
    }

    public function deliveryDeleteMethod(int $id) {
        DB::table('delivery_methods')->where('id', $id)->delete();
        return back()->with('success', 'Method deleted.');
    }

    // ═══════════════════════════════════════
    // PAYMENT METHODS
    // ═══════════════════════════════════════
    public function paymentIndex()
    {
        $methods = collect([]);
        try { $methods = DB::table('payment_methods')->orderBy('sort_order')->get(); } catch (\Exception $e) {}
        return view('admin.payment.index', compact('methods'));
    }

    public function paymentSave(Request $request)
    {
        try {
        $ids = $request->input('pm_id', []);
        foreach ($ids as $i => $id) {
            $configRaw = $request->input("pm_config_{$id}", []);
            $config = is_array($configRaw) ? $configRaw : [];

            $data = [
                'name' => $request->input("pm_name.{$i}"),
                'description' => $request->input("pm_description.{$i}"),
                'is_active' => (bool)$request->input("pm_active.{$i}"),
                'is_test_mode' => (bool)$request->input("pm_test_mode.{$i}"),
                'sort_order' => $i + 1,
                'config' => json_encode($config),
                'updated_at' => now(),
            ];
            DB::table('payment_methods')->where('id', $id)->update($data);
        }
        return back()->with('success', 'Payment methods saved!');
        } catch (\Exception $e) { return back()->with('error', 'Error: '.$e->getMessage()); }
    }

    // ═══════════════════════════════════════
    // FOOTER LINKS
    // ═══════════════════════════════════════
    public function footerLinksIndex()
    {
        $links = collect([]);
        try { $links = DB::table('footer_links')->orderBy('sort_order')->get(); } catch (\Exception $e) {}
        return view('admin.footer-links.index', compact('links'));
    }

    public function footerLinksSave(Request $request)
    {
        try {
        // Delete removed links
        $keepIds = array_filter($request->input('link_id', []), fn($id) => $id !== 'new');
        if (!empty($keepIds)) {
            DB::table('footer_links')->whereNotIn('id', $keepIds)->delete();
        } else {
            DB::table('footer_links')->truncate();
        }

        $ids = $request->input('link_id', []);
        $labels = $request->input('link_label', []);
        $urls = $request->input('link_url', []);
        $targets = $request->input('link_target', []);
        $actives = $request->input('link_active', []);

        foreach ($ids as $i => $id) {
            if (!trim($labels[$i] ?? '')) continue;
            $data = [
                'label' => trim($labels[$i]),
                'url' => trim($urls[$i] ?? '#'),
                'column' => 'useful_links',
                'target' => $targets[$i] ?? '_self',
                'is_active' => in_array((string)($id ?: "new_$i"), $actives),
                'sort_order' => $i + 1,
                'updated_at' => now(),
            ];
            if ($id && $id !== 'new') {
                DB::table('footer_links')->where('id', $id)->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('footer_links')->insert($data);
            }
        }
        return back()->with('success', 'Footer links saved!');
        } catch (\Exception $e) { return back()->with('error', 'Error: '.$e->getMessage()); }
    }

    // ═══════════════════════════════════════
    // NAVIGATION LINKS
    // ═══════════════════════════════════════
    public function navLinksIndex()
    {
        $links = collect([]);
        try { $links = DB::table('nav_links')->orderBy('sort_order')->get(); } catch (\Exception $e) {}
        return view('admin.nav-links.index', compact('links'));
    }

    public function navLinksSave(Request $request)
    {
        try {
        $keepIds = array_filter($request->input('nav_id', []), fn($id) => $id !== 'new');
        if (!empty($keepIds)) {
            DB::table('nav_links')->whereNotIn('id', $keepIds)->delete();
        } else {
            DB::table('nav_links')->truncate();
        }

        $ids = $request->input('nav_id', []);
        $labels = $request->input('nav_label', []);
        $urls = $request->input('nav_url', []);
        $actives = $request->input('nav_active', []);

        foreach ($ids as $i => $id) {
            if (!trim($labels[$i] ?? '')) continue;
            $data = [
                'label' => trim($labels[$i]),
                'url' => trim($urls[$i] ?? '/'),
                'type' => 'header',
                'is_active' => in_array((string)($id ?: "new_$i"), $actives),
                'sort_order' => $i + 1,
                'updated_at' => now(),
            ];
            if ($id && $id !== 'new') {
                DB::table('nav_links')->where('id', $id)->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('nav_links')->insert($data);
            }
        }
        return back()->with('success', 'Navigation links saved!');
        } catch (\Exception $e) { return back()->with('error', 'Error: '.$e->getMessage()); }
    }

    // ═══════════════════════════════════════
    // PROMO CODES
    // ═══════════════════════════════════════
    public function promoIndex()
    {
        $promos = collect([]);
        try { $promos = DB::table('promo_codes')->orderBy('created_at', 'desc')->get(); } catch (\Exception $e) {}
        return view('admin.promos.index', compact('promos'));
    }

    public function promoStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
        ]);
        DB::table('promo_codes')->insert([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_uses' => $request->max_uses ?: null,
            'starts_at' => $request->starts_at ?: null,
            'expires_at' => $request->expires_at ?: null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', "Promo code '{$request->code}' created!");
    }

    public function promoToggle(int $id)
    {
        try {
            $promo = DB::table('promo_codes')->where('id', $id)->first();
            if ($promo) {
                DB::table('promo_codes')->where('id', $id)->update([
                    'is_active' => !$promo->is_active,
                    'updated_at' => now(),
                ]);
            }
            return back()->with('success', 'Promo code updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function promoDelete(int $id)
    {
        DB::table('promo_codes')->where('id', $id)->delete();
        return back()->with('success', 'Promo code deleted.');
    }
}
