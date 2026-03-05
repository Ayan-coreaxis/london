<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add phone & company to users if not already present
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'company')) {
                    $table->string('company')->nullable()->after('phone');
                }
            });
        }

        // Create artwork uploads folder
        $artworkDir = public_path('uploads/artwork');
        if (!file_exists($artworkDir)) {
            mkdir($artworkDir, 0755, true);
        }
    }

    public function down(): void
    {
        //
    }
};
