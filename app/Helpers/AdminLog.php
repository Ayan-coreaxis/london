<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminLog
{
    public static function log(string $action, ?string $entityType = null, ?int $entityId = null, ?string $details = null): void
    {
        try {
            $admin = Auth::guard('admin')->user();
            DB::table('admin_activity_log')->insert([
                'admin_id' => $admin->id ?? null,
                'admin_name' => $admin->name ?? 'System',
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'details' => $details,
                'ip_address' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}
    }
}
