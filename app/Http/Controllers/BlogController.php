<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\SiteSetting;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Blog::published()->ordered();
            if ($request->filled('category')) $query->where('category',$request->category);
            $blogs      = $query->paginate(9)->withQueryString();
            $categories = Blog::published()->whereNotNull('category')->distinct()->pluck('category');
        } catch (\Exception $e) {
            $blogs      = collect([]);
            $categories = collect([]);
        }
        $settings = SiteSetting::allKeyed();
        return view('pages.blog', compact('blogs','categories','settings'));
    }

    public function show(string $slug)
    {
        try {
            $blog    = Blog::where('slug',$slug)->where('status','published')->firstOrFail();
            $related = Blog::published()->where('id','!=',$blog->id)->ordered()->limit(3)->get();
        } catch (\Exception $e) {
            abort(404);
        }
        $settings = SiteSetting::allKeyed();
        return view('pages.blog-single', compact('blog','related','settings'));
    }
}
