<?php
/**
 * AdminDesignController.php
 * 
 * Admin Design Settings — sirf colors aur gradients.
 * Typography section bilkul nahi hai — fonts CSS mein hardcoded hain.
 * 
 * Place at: app/Http/Controllers/Admin/DesignController.php
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignController extends Controller
{
    /**
     * Design settings page — Typography section completely exclude
     */
    public function index()
    {
        $settings = DB::table('site_settings')
            ->where('group', 'design')
            ->whereNotIn('type', ['font', 'readonly', 'number'])
            ->get()
            ->keyBy('key');

        return view('admin.design.index', compact('settings'));
    }

    /**
     * Settings save karo — font keys kabhi update nahi hongi
     */
    public function update(Request $request)
    {
        $blockedKeys = ['font_heading', 'font_body', 'font_size_base'];
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            if (in_array($key, $blockedKeys)) continue;

            $setting = DB::table('site_settings')->where('key', $key)->first();
            if (!$setting) continue;
            if (in_array($setting->type, ['font', 'readonly', 'number'])) continue;

            DB::table('site_settings')
                ->where('key', $key)
                ->update(['value' => $value, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Design settings updated!');
    }

    /**
     * Page edits — sirf bold/italic/highlight, fontFamily nahi
     */
    public function savePageEdit(Request $request)
    {
        $request->validate([
            'page_path' => 'required|string',
            'selector'  => 'required|string',
            'styles'    => 'required|array',
            'content'   => 'nullable|string',
        ]);

        $allowedStyles = ['fontWeight', 'fontStyle', 'backgroundColor', 'fontSize', 'textDecoration', 'color'];
        $safeStyles = array_intersect_key($request->styles, array_flip($allowedStyles));

        DB::table('page_edits')->updateOrInsert(
            ['page_path' => $request->page_path, 'selector' => $request->selector],
            ['styles' => json_encode($safeStyles), 'content' => $request->content, 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }
}
