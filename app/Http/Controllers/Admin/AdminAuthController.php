<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard("admin")->check()) return redirect()->route("admin.dashboard");
        return view("admin.auth.login");
    }

    public function login(Request $request)
    {
        $request->validate(["email"=>"required|email","password"=>"required"]);

        if (!Auth::guard("admin")->attempt($request->only("email","password"), $request->boolean("remember"))) {
            throw ValidationException::withMessages(["email"=>"Invalid admin credentials."]);
        }

        $request->session()->regenerate();

        // Update last login
        \Illuminate\Support\Facades\DB::table("admins")
            ->where("id", Auth::guard("admin")->id())
            ->update(["last_login_at"=>now()]);

        return redirect()->route("admin.dashboard");
    }

    public function logout(Request $request)
    {
        Auth::guard("admin")->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("admin.login");
    }
}
