<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod("GET") && !$request->is("admin/*")) {
            try {
                DB::table("visitors")->insert([
                    "ip_address"   => $request->ip(),
                    "user_agent"   => substr($request->userAgent() ?? "", 0, 255),
                    "page"         => substr($request->path(), 0, 255),
                    "referer"      => substr($request->headers->get("referer",""), 0, 255),
                    "user_id"      => Auth::id(),
                    "visited_date" => now()->toDateString(),
                    "created_at"   => now(),
                    "updated_at"   => now(),
                ]);
            } catch ($e) {}
        }
        return $next($request);
    }
}
