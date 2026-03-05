<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "email"    => "required|email",
            "password" => "required|string",
        ]);

        if (!Auth::attempt($request->only("email","password"), $request->boolean("remember"))) {
            throw ValidationException::withMessages(["email" => "These credentials do not match our records."]);
        }

        // Pull intended BEFORE regenerate (regenerate wipes session)
        $intended = $request->session()->pull("url.intended");

        $request->session()->regenerate();

        // 1. Laravel's intended URL (e.g. user tried to access /checkout directly)
        if ($intended) {
            return redirect($intended);
        }

        // 2. Hidden form field redirect=checkout (from checkout-login page)
        $redirect = $request->input("redirect", "");
        if ($redirect === "checkout") {
            return redirect()->route("checkout");
        }

        // 3. Normal login (header button) → order tracking dashboard
        return redirect()->route("user.dashboard");
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("home");
    }
}
