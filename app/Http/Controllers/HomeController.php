<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Blog;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products (first 4 active)
        try {
            $dbProducts = DB::select("SELECT id,name,slug,image1,base_price FROM products WHERE status='active' ORDER BY sort_order ASC, created_at ASC LIMIT 4");
        } catch (\Exception $e) { $dbProducts = []; }

        $colors = ['pt-1','pt-2','pt-3','pt-4'];
        $staticImages = ['images/one.webp','images/two.webp','images/three.jpg','images/four.webp'];
        $staticNames  = ['Leaflets & Flyers','Greeting Cards','Business Cards','Postcards'];

        if (!empty($dbProducts)) {
            $products = [];
            foreach ($dbProducts as $i => $p) {
                $products[] = [
                    'name'        => $p->name,
                    'image'       => $p->image1 ?: $staticImages[$i % 4],
                    'base_price'  => $p->base_price ?? null,
                    'thumb_class' => $colors[$i % 4],
                    'url'         => route('product.show', $p->slug),
                ];
            }
        } else {
            $products = [];
            foreach ($staticNames as $i => $name) {
                $products[] = ['name'=>$name,'image'=>$staticImages[$i],'base_price'=>null,'thumb_class'=>$colors[$i],'url'=>route('products')];
            }
        }

        // Blogs (from DB or static fallback)
        $blogs = $this->getBlogs();

        // Site settings for hero / promo
        $settings = SiteSetting::allKeyed();

        // Delivery methods for home page section
        $deliveryMethods = collect([]);
        try { $deliveryMethods = DB::table('delivery_methods')->where('is_active', true)->orderBy('sort_order')->take(4)->get(); } catch (\Exception $e) {}

        return view('pages.home', compact('products','blogs','settings','deliveryMethods'));
    }

    public function allProducts(Request $request)
    {
        $colors = ['pt-1','pt-2','pt-3','pt-4','pt-5','pt-6','pt-7','pt-8'];

        try {
            $categories = Product::where('status','active')
                ->whereNotNull('category')->distinct()->orderBy('category')
                ->pluck('category')->filter()->values()->toArray();
        } catch (\Exception $e) { $categories = []; }

        try {
            $query = Product::select('id','name','slug','category','image1','image2','base_price')
                ->where('status','active');
            if ($request->filled('category')) $query->where('category', $request->category);
            if ($request->filled('q'))        $query->where('name','like','%'.$request->q.'%');
            $products = $query->orderBy('sort_order','asc')->orderBy('created_at','asc')->paginate(12)->withQueryString();
        } catch (\Exception $e) {
            $products   = collect([]);
            $categories = [];
        }

        $blogs    = $this->getBlogs();
        $settings = SiteSetting::allKeyed();
        return view('pages.all-products', compact('products','blogs','categories','settings'));
    }

    public function blog(Request $request)
    {
        $category = $request->get('category');
        $categories = collect([]);
        $blogs = collect([]);

        try {
            $categories = Blog::published()->distinct()->whereNotNull('category')->pluck('category')->filter();

            $query = Blog::published()->ordered();
            if ($category) {
                $query->where('category', $category);
            }
            $blogs = $query->paginate(12);
        } catch (\Exception $e) {}

        $settings = SiteSetting::allKeyed();
        return view('pages.blog', compact('blogs', 'categories', 'settings'));
    }

    private function getBlogs(): array
    {
        try {
            $rows = Blog::published()->ordered()->limit(4)->get();
            if ($rows->count() > 0) {
                $thumbClasses = ['bt-1','bt-2','bt-3','bt-4'];
                return $rows->map(fn($b,$i) => [
                    'id'          => $b->id,
                    'title'       => $b->title,
                    'excerpt'     => $b->excerpt,
                    'image'       => $b->image,
                    'category'    => $b->category,
                    'thumb_class' => $thumbClasses[$i % 4],
                    'url'         => route('blog.show', $b->slug),
                ])->toArray();
            }
        } catch (\Exception $e) {}

        // Static fallback
        return [
            ['id'=>null,'title'=>'Smart Ways to Save Money on Printing Costs','excerpt'=>null,'image'=>'images/Blog1.webp','category'=>'Tips','thumb_class'=>'bt-1','url'=>route('blog')],
            ['id'=>null,'title'=>'The Ultimate Guide to Frame Sizes for Artwork','excerpt'=>null,'image'=>'images/Blog2.webp','category'=>'Guide','thumb_class'=>'bt-2','url'=>route('blog')],
            ['id'=>null,'title'=>'How to Write Wedding RSVP Cards: Wording & Design Tips','excerpt'=>null,'image'=>'images/Blog3.webp','category'=>'Design','thumb_class'=>'bt-3','url'=>route('blog')],
            ['id'=>null,'title'=>'How to Design a Banner in Photoshop','excerpt'=>null,'image'=>'images/Blog4.webp','category'=>'Tutorial','thumb_class'=>'bt-4','url'=>route('blog')],
        ];
    }
}
