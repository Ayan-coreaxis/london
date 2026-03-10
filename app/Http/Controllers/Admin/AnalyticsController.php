<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days        = (int)$request->get('days', 30);
        $defaultDays = 30;

        $periodOrders  = DB::selectOne("SELECT COUNT(*) as c FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$days])->c ?? 0;
        $periodRevenue = DB::selectOne("SELECT COALESCE(SUM(total),0) as s FROM orders WHERE status != 'cancelled' AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$days])->s ?? 0;
        $newCustomers  = DB::selectOne("SELECT COUNT(*) as c FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$days])->c ?? 0;

        $revenueByDay = DB::select("SELECT DATE(created_at) as date_val, DATE_FORMAT(created_at,'%d %b') as day, COALESCE(SUM(total),0) as revenue FROM orders WHERE status != 'cancelled' AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY DATE(created_at), DATE_FORMAT(created_at,'%d %b') ORDER BY DATE(created_at)", [$days]);

        $ordersByDay = DB::select("SELECT DATE(created_at) as date_val, DATE_FORMAT(created_at,'%d %b') as day, COUNT(*) as count FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY DATE(created_at), DATE_FORMAT(created_at,'%d %b') ORDER BY DATE(created_at)", [$days]);

        $topProducts = DB::select("SELECT oi.product_name, SUM(oi.quantity) as total_qty, SUM(oi.line_total) as total_revenue FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE o.status != 'cancelled' AND o.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY oi.product_name ORDER BY total_revenue DESC LIMIT 5", [$days]);

        $statusBreakdown = DB::select("SELECT status, COUNT(*) as count FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY status", [$days]);

        return view('admin.analytics.index', compact('days','defaultDays','periodOrders','periodRevenue','newCustomers','revenueByDay','ordersByDay','topProducts','statusBreakdown'));
    }
}
