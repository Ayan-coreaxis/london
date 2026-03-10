<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $query  = "SELECT u.*,
                    COALESCE((SELECT COUNT(*) FROM orders WHERE user_id=u.id),0) as order_count,
                    COALESCE((SELECT SUM(total) FROM orders WHERE user_id=u.id AND status!='cancelled'),0) as total_spent
                   FROM users u WHERE 1=1";
        $params = [];
        if ($search) {
            $query .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.company LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%"];
        }
        $query .= " ORDER BY u.created_at DESC";
        $users  = DB::select($query, $params);
        return view('admin.users.index', compact('users', 'search'));
    }

    public function show(int $id)
    {
        $user   = DB::selectOne("SELECT * FROM users WHERE id=?", [$id]);
        if (!$user) abort(404);
        $orders = DB::select("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC", [$id]);
        return view('admin.users.show', compact('user', 'orders'));
    }
}
