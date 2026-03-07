<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderNoteController extends Controller
{
    // Customer sends a note on their order
    public function customerStore(Request $request, int $orderId)
    {
        $user = Auth::user();
        $order = DB::selectOne("SELECT * FROM orders WHERE id=? AND user_id=?", [$orderId, $user->id]);
        if (!$order) abort(404);

        $request->validate(['message' => 'required|string|max:2000']);

        try { DB::table('order_notes')->insert([
            'order_id' => $orderId, 'message' => $request->message,
            'author_type' => 'customer', 'author_id' => $user->id,
            'author_name' => $user->name, 'is_internal' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        } catch (\Exception \$e) { return back()->with('error', 'Could not send message.'); }
        return back()->with('success', 'Message sent!');
    }

    // Admin sends a note (can be internal or visible to customer)
    public function adminStore(Request $request, int $orderId)
    {
        $order = DB::selectOne("SELECT * FROM orders WHERE id=?", [$orderId]);
        if (!$order) abort(404);

        $request->validate(['message' => 'required|string|max:5000']);

        $admin = Auth::guard('admin')->user();
        try { DB::table('order_notes')->insert([
            'order_id' => $orderId, 'message' => $request->message,
            'author_type' => 'admin', 'author_id' => $admin->id ?? null,
            'author_name' => $admin->name ?? 'Admin',
            'is_internal' => (bool)$request->input('is_internal', false),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        } catch (\Exception \$e) { return back()->with('error', 'Could not add note.'); }
        return back()->with('success', 'Note added.');
    }
}
