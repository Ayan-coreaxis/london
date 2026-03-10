<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::select("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON p.category = c.name GROUP BY c.id, c.name, c.slug, c.description, c.sort_order, c.created_at, c.updated_at ORDER BY c.sort_order, c.name");
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $slug = $this->uniqueSlug(Str::slug($request->name));
        DB::insert(
            "INSERT INTO categories (name, slug, description, sort_order, created_at, updated_at) VALUES (?,?,?,?,NOW(),NOW())",
            [trim($request->name), $slug, $request->description ?? '', $request->sort_order ?? 0]
        );
        return redirect()->route('admin.categories.index')->with('success', 'Category "' . $request->name . '" added!');
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $old = DB::selectOne("SELECT name FROM categories WHERE id=?", [$id]);
        DB::update(
            "UPDATE categories SET name=?, description=?, sort_order=?, updated_at=NOW() WHERE id=?",
            [trim($request->name), $request->description ?? '', $request->sort_order ?? 0, $id]
        );
        // Update products that use old category name
        if ($old && $old->name !== trim($request->name)) {
            DB::update("UPDATE products SET category=? WHERE category=?", [trim($request->name), $old->name]);
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category updated!');
    }

    public function destroy(int $id)
    {
        $cat = DB::selectOne("SELECT name FROM categories WHERE id=?", [$id]);
        $count = DB::selectOne("SELECT COUNT(*) as c FROM products WHERE category=?", [$cat->name ?? '']);
        if ($count && $count->c > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete — ' . $count->c . ' product(s) use this category!');
        }
        DB::delete("DELETE FROM categories WHERE id=?", [$id]);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }

    private function uniqueSlug(string $s): string {
        $o = $s; $i = 1;
        while (DB::selectOne("SELECT id FROM categories WHERE slug=?", [$s])) $s = $o . '-' . $i++;
        return $s;
    }
}