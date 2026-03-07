<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get("status","");
        $search = $request->get("search","");
        $query  = "SELECT * FROM orders WHERE 1=1";
        $params = [];
        if ($status) { $query .= " AND status = ?"; $params[] = $status; }
        if ($search) {
            $query .= " AND (order_ref LIKE ? OR email LIKE ? OR CONCAT(first_name,' ',last_name) LIKE ?)";
            $params = array_merge($params, ["%$search%","%$search%","%$search%"]);
        }
        $query .= " ORDER BY created_at DESC LIMIT 100";
        $orders = DB::select($query, $params);
        $counts = [];
        foreach (DB::select("SELECT status, COUNT(*) as c FROM orders GROUP BY status") as $r) {
            $counts[$r->status] = $r->c;
        }
        return view("admin.orders.index", compact("orders","status","search","counts"));
    }

    public function show(int $id)
    {
        $order = DB::selectOne("SELECT * FROM orders WHERE id = ?", [$id]);
        if (!$order) abort(404);
        $items   = DB::select("SELECT * FROM order_items WHERE order_id = ?", [$id]);
        $history = [];
        try { $history = DB::select("SELECT osh.*, a.name as admin_name FROM order_status_history osh LEFT JOIN admins a ON a.id=osh.admin_id WHERE osh.order_id=? ORDER BY osh.created_at DESC", [$id]); } catch(\Exception $e){}

        // Load all artwork files
        $artworkFiles = [];
        try { $artworkFiles = DB::select("SELECT oa.*, oi.product_name FROM order_artwork oa LEFT JOIN order_items oi ON oi.id = oa.order_item_id WHERE oa.order_id = ? ORDER BY oa.created_at DESC", [$id]); } catch(\Exception $e){}

        // Load order notes (all for admin)
        $orderNotes = [];
        try { $orderNotes = DB::select("SELECT * FROM order_notes WHERE order_id = ? ORDER BY created_at ASC", [$id]); } catch(\Exception $e){}

        return view("admin.orders.show", compact("order","items","history","artworkFiles","orderNotes"));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(["status"=>"required|in:pending,confirmed,in_production,dispatched,completed,cancelled"]);
        DB::update("UPDATE orders SET status=?, updated_at=NOW() WHERE id=?", [$request->status, $id]);
        try {
            DB::table("order_status_history")->insert([
                "order_id"   => $id,
                "status"     => $request->status,
                "note"       => $request->note,
                "admin_id"   => Auth::guard("admin")->id(),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        } catch(\Exception $e){}

        // Send email notification to customer
        try {
            $order = DB::selectOne("SELECT * FROM orders WHERE id = ?", [$id]);
            if ($order && $order->email) {
                Mail::to($order->email)->send(new OrderStatusUpdated($order, $request->status));
            }
        } catch(\Exception $e){
            \Illuminate\Support\Facades\Log::error('Status email error: ' . $e->getMessage());
        }

        // Log admin activity
        try { \App\Helpers\AdminLog::log('updated_order_status', 'order', $id, "Status changed to {$request->status}"); } catch (\Exception $e) {}

        return back()->with("success","Order status updated to {$request->status}! Customer has been notified by email.");
    }
}
