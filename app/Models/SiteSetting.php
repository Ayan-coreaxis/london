<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteSetting extends Model
{
    protected $fillable = ["key","value","type","group","label"];

    public static function get(string $key, string $default = "")
    {
        try {
            $row = DB::table("site_settings")->where("key", $key)->first();
            return $row ? $row->value : $default;
        } catch (\Exception $e) { return $default; }
    }

    public static function set(string $key, string $value): void
    {
        DB::table("site_settings")->where("key", $key)->update(["value"=>$value,"updated_at"=>now()]);
    }

    public static function allKeyed(): array
    {
        try {
            $rows = DB::table("site_settings")->get();
            $out  = [];
            foreach ($rows as $r) { $out[$r->key] = $r->value; }
            return $out;
        } catch (\Exception $e) { return []; }
    }
}
