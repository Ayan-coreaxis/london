<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = [];
        try { $items = DB::select(
            "SELECT w.id as wishlist_id, w.created_at as added_at, p.id, p.name, p.slug, p.image1, p.base_price, p.category
             FROM wishlists w JOIN products p ON p.id = w.product_id
             WHERE w.user_id = ? AND p.status = 'active'
             ORDER BY w.created_at DESC", [$user->id]
        );
        } catch (\Exception \$e) {}
        return view('user.wishlist', compact('items'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);
        $userId = Auth::id();
        $productId = $request->product_id;

        $exists = DB::selectOne("SELECT id FROM wishlists WHERE user_id=? AND product_id=?", [$userId, $productId]);

        if ($exists) {
            DB::delete("DELETE FROM wishlists WHERE id=?", [$exists->id]);
            $added = false;
        } else {
            DB::insert("INSERT INTO wishlists (user_id, product_id, created_at, updated_at) VALUES (?,?,NOW(),NOW())", [$userId, $productId]);
            $added = true;
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'added' => $added, 'message' => $added ? 'Added to wishlist!' : 'Removed from wishlist.']);
        }
        return back()->with('success', $added ? 'Added to wishlist!' : 'Removed from wishlist.');
    }

    public function remove(int $id)
    {
        DB::delete("DELETE FROM wishlists WHERE id=? AND user_id=?", [$id, Auth::id()]);
        return back()->with('success', 'Removed from wishlist.');
    }
}
