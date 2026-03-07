<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders   = DB::selectOne("SELECT COUNT(*) as c FROM orders")->c ?? 0;
        $totalRevenue  = DB::selectOne("SELECT COALESCE(SUM(total),0) as s FROM orders WHERE status != 'cancelled'")->s ?? 0;
        $totalUsers    = DB::selectOne("SELECT COUNT(*) as c FROM users")->c ?? 0;
        $totalProducts = DB::selectOne("SELECT COUNT(*) as c FROM products WHERE status='active'")->c ?? 0;

        $todayVisitors = 0; $monthVisitors = 0;
        try {
            $todayVisitors = DB::selectOne("SELECT COUNT(DISTINCT ip_address) as c FROM visitors WHERE visited_date = CURDATE()")->c ?? 0;
            $monthVisitors = DB::selectOne("SELECT COUNT(DISTINCT ip_address) as c FROM visitors WHERE MONTH(visited_date)=MONTH(NOW()) AND YEAR(visited_date)=YEAR(NOW())")->c ?? 0;
        } catch (\Exception $e) {}

        $recentOrders   = DB::select("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
        $ordersByStatus = DB::select("SELECT status, COUNT(*) as count FROM orders GROUP BY status");

        $revenueChart = [];
        try {
            $revenueChart = DB::select("SELECT DATE_FORMAT(created_at,'%b') as month, SUM(total) as revenue FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) AND status != 'cancelled' GROUP BY DATE_FORMAT(created_at,'%Y-%m'), DATE_FORMAT(created_at,'%b') ORDER BY MIN(created_at)");
        } catch (\Exception $e) {}

        return view("admin.dashboard.index", compact(
            "totalOrders","totalRevenue","totalUsers","totalProducts",
            "todayVisitors","monthVisitors","recentOrders","ordersByStatus",
            "revenueChart"
        ));
    }

    public function activityLog()
    {
        $logs = [];
        try {
            $logs = DB::select("SELECT * FROM admin_activity_log ORDER BY created_at DESC LIMIT 200");
        } catch (\Exception $e) {}
        return view("admin.activity-log", compact("logs"));
    }
}
