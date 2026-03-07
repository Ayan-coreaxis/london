<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = [];
        try { $addresses = DB::select("SELECT * FROM user_addresses WHERE user_id=? ORDER BY is_default DESC, created_at DESC", [Auth::id()]);
        } catch (\Exception \$e) {}
        return view('user.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100',
            'address_line1' => 'required|string|max:255', 'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
        ]);

        $userId = Auth::id();

        // If marking as default, unset others
        if ($request->is_default) {
            DB::update("UPDATE user_addresses SET is_default=0 WHERE user_id=?", [$userId]);
        }

        DB::table('user_addresses')->insert([
            'user_id' => $userId, 'label' => $request->label ?? 'Home',
            'first_name' => $request->first_name, 'last_name' => $request->last_name,
            'company' => $request->company, 'phone' => $request->phone,
            'address_line1' => $request->address_line1, 'address_line2' => $request->address_line2,
            'city' => $request->city, 'postcode' => $request->postcode,
            'country' => $request->country ?? 'GB', 'is_default' => (bool)$request->is_default,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return back()->with('success', 'Address saved!');
    }

    public function update(Request $request, int $id)
    {
        $addr = DB::selectOne("SELECT * FROM user_addresses WHERE id=? AND user_id=?", [$id, Auth::id()]);
        if (!$addr) abort(404);

        $request->validate([
            'first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100',
            'address_line1' => 'required|string|max:255', 'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
        ]);

        if ($request->is_default) {
            DB::update("UPDATE user_addresses SET is_default=0 WHERE user_id=?", [Auth::id()]);
        }

        DB::table('user_addresses')->where('id', $id)->update([
            'label' => $request->label ?? 'Home',
            'first_name' => $request->first_name, 'last_name' => $request->last_name,
            'company' => $request->company, 'phone' => $request->phone,
            'address_line1' => $request->address_line1, 'address_line2' => $request->address_line2,
            'city' => $request->city, 'postcode' => $request->postcode,
            'country' => $request->country ?? 'GB', 'is_default' => (bool)$request->is_default,
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Address updated!');
    }

    public function destroy(int $id)
    {
        DB::delete("DELETE FROM user_addresses WHERE id=? AND user_id=?", [$id, Auth::id()]);
        return back()->with('success', 'Address deleted.');
    }

    public function setDefault(int $id)
    {
        $userId = Auth::id();
        DB::update("UPDATE user_addresses SET is_default=0 WHERE user_id=?", [$userId]);
        DB::update("UPDATE user_addresses SET is_default=1 WHERE id=? AND user_id=?", [$id, $userId]);
        return back()->with('success', 'Default address updated.');
    }

    // API: get addresses for checkout autofill
    public function getForCheckout()
    {
        $addresses = DB::select("SELECT * FROM user_addresses WHERE user_id=? ORDER BY is_default DESC", [Auth::id()]);
        return response()->json($addresses);
    }
}
