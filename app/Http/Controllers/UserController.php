<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function dashboard()
    {
        $user   = Auth::user();
        $orders = DB::select(
            "SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id=o.id) as item_count
             FROM orders o WHERE o.user_id = ? ORDER BY o.created_at DESC",
            [$user->id]
        );
        return view("user.dashboard", compact("user", "orders"));
    }

    public function orderDetail(int $id)
    {
        $user  = Auth::user();
        $order = DB::selectOne("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$id, $user->id]);
        if (!$order) abort(404);
        $items = DB::select("SELECT * FROM order_items WHERE order_id = ?", [$id]);
        $history = [];
        try {
            $history = DB::select("SELECT * FROM order_status_history WHERE order_id = ? ORDER BY created_at DESC", [$id]);
        } catch (\Exception $e) {}
        return view("user.order-detail", compact("order", "items", "history", "user"));
    }

    public function profile()
    {
        $user = Auth::user();
        return view("user.profile", compact("user"));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'company'    => 'nullable|string|max:255',
        ]);
        DB::table('users')->where('id', $user->id)->update([
            'name'       => $request->first_name . ' ' . $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'company'    => $request->company,
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }
        DB::table('users')->where('id', $user->id)->update([
            'password'   => Hash::make($request->password),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Password changed successfully!');
    }

    public function cancelOrder(int $id)
    {
        $user  = Auth::user();
        $order = DB::selectOne("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$id, $user->id]);
        if (!$order) abort(404);
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This order cannot be cancelled — it is already ' . $order->status . '.');
        }
        DB::update("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ?", [$id]);
        try {
            DB::table('order_status_history')->insert([
                'order_id'   => $id,
                'status'     => 'cancelled',
                'note'       => 'Cancelled by customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}
        return back()->with('success', 'Your order has been cancelled.');
    }
}
