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

        // Rate limiting — max 5 attempts per 5 minutes
        $key = 'admin-login:' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 5;

        if (\Illuminate\Support\Facades\Cache::get($key, 0) >= $maxAttempts) {
            throw ValidationException::withMessages([
                "email" => "Too many login attempts. Please try again in a few minutes."
            ]);
        }

        if (!Auth::guard("admin")->attempt($request->only("email","password"), $request->boolean("remember"))) {
            \Illuminate\Support\Facades\Cache::put($key, \Illuminate\Support\Facades\Cache::get($key, 0) + 1, now()->addMinutes($decayMinutes));
            throw ValidationException::withMessages(["email"=>"Invalid admin credentials."]);
        }

        \Illuminate\Support\Facades\Cache::forget($key);
        $request->session()->regenerate();

        // Update last login
        \Illuminate\Support\Facades\DB::table("admins")
            ->where("id", Auth::guard("admin")->id())
            ->update(["last_login_at"=>now()]);

        try { \App\Helpers\AdminLog::log('admin_login', 'admin', Auth::guard('admin')->id(), 'Admin logged in'); } catch (\Exception $e) {}

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
