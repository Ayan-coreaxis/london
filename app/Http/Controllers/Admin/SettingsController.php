<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table("site_settings")->orderBy("group")->orderBy("id")->get();
        $grouped  = [];
        foreach ($settings as $s) { $grouped[$s->group][] = $s; }
        return view("admin.settings.index", compact("grouped"));
    }

    public function update(Request $request)
    {
        $skip = ['_token', '_method', 'hero_image_upload', 'header_logo_upload', 'font_heading', 'font_body', 'font_size_base'];

        // Handle hero image upload
        if ($request->hasFile('hero_image_upload')) {
            $path = $this->uploadFile($request->file('hero_image_upload'), 'uploads/settings');
            DB::table('site_settings')->where('key','hero_image')
                ->update(['value'=>$path,'updated_at'=>now()]);
            $skip[] = 'hero_image'; // don't overwrite with hidden field
        }

        // Handle logo upload
        if ($request->hasFile('header_logo_upload')) {
            $path = $this->uploadFile($request->file('header_logo_upload'), 'uploads/settings');
            DB::table('site_settings')->where('key','header_logo')
                ->update(['value'=>$path,'updated_at'=>now()]);
            $skip[] = 'header_logo';
        }

        foreach ($request->except($skip) as $key => $value) {
            // Only update existing keys — never auto-insert
            DB::table('site_settings')
                ->where('key', $key)
                ->update(['value' => $value ?? '', 'updated_at' => now()]);
        }

        try { \App\Helpers\AdminLog::log('updated_settings', 'settings', null, 'Site settings updated'); } catch (\Exception $e) {}
        return back()->with('success', 'Settings saved! Changes are live on the website.');
    }

    private function uploadFile($file, string $folder): string
    {
        $dir = public_path($folder);
        if (!file_exists($dir)) mkdir($dir, 0755, true);
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return $folder.'/'.$filename;
    }
}
