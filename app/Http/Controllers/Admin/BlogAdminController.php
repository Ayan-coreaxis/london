<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogAdminController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('sort_order')->orderBy('created_at','desc')->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create() { return view('admin.blogs.form', ['blog'=>null]); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'excerpt'    => 'nullable|string',
            'body'       => 'nullable|string',
            'category'   => 'nullable|string|max:100',
            'author'     => 'nullable|string|max:100',
            'status'     => 'required|in:published,draft',
            'sort_order' => 'nullable|integer',
        ]);
        $data['slug'] = Str::slug($request->title);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'uploads/blogs';
            if (!file_exists(public_path($path))) mkdir(public_path($path), 0755, true);
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path($path), $filename);
            $data['image'] = $path.'/'.$filename;
        }
        Blog::create($data);
        return redirect()->route('admin.blogs.index')->with('success','Blog post created!');
    }

    public function edit(int $id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.form', compact('blog'));
    }

    public function update(Request $request, int $id)
    {
        $blog = Blog::findOrFail($id);
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'excerpt'    => 'nullable|string',
            'body'       => 'nullable|string',
            'category'   => 'nullable|string|max:100',
            'author'     => 'nullable|string|max:100',
            'status'     => 'required|in:published,draft',
            'sort_order' => 'nullable|integer',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'uploads/blogs';
            if (!file_exists(public_path($path))) mkdir(public_path($path), 0755, true);
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path($path), $filename);
            $data['image'] = $path.'/'.$filename;
        }
        $blog->update($data);
        return redirect()->route('admin.blogs.index')->with('success','Blog post updated!');
    }

    public function destroy(int $id)
    {
        Blog::findOrFail($id)->delete();
        return redirect()->route('admin.blogs.index')->with('success','Blog post deleted.');
    }
}
