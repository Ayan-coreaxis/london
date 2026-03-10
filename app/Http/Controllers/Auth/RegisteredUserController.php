<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Mail\WelcomeEmail;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "first_name"   => "required|string|max:255",
            "last_name"    => "required|string|max:255",
            "email"        => "required|email|unique:users,email",
            "password"     => ["required","confirmed",Password::min(8)],
            "phone"        => "nullable|string|max:20",
            "company_name" => "nullable|string|max:255",
        ]);

        $intended = $request->session()->pull("url.intended");

        $user = User::create([
            "name"     => $request->first_name . " " . $request->last_name,
            "email"    => $request->email,
            "password" => Hash::make($request->password),
            "phone"    => $request->phone,
            "company"  => $request->company_name,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Welcome email failed: ' . $e->getMessage());
        }

        if ($intended) return redirect($intended);
        $redirect = $request->input("redirect", "");
        if ($redirect === "checkout") return redirect()->route("checkout");
        $cart = session("cart", []);
        if (!empty($cart)) return redirect()->route("checkout");
        return redirect()->route("user.dashboard");
    }
}
