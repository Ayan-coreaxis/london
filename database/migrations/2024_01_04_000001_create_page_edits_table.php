<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('page_edits')) {
            Schema::create('page_edits', function (Blueprint $table) {
                $table->id();
                $table->string('page_path');        // e.g. "/"  "/products"
                $table->string('selector');         // CSS selector e.g. ".hero-left h1"
                $table->longText('styles');         // JSON: {fontWeight, fontSize, fontStyle, backgroundColor}
                                                    // NOTE: fontFamily intentionally NOT allowed here
                                                    //       Font family is fixed via site_settings (type=readonly)
                                                    //       Only bold/italic/highlight/size changes allowed
                $table->longText('content')->nullable(); // edited text content
                $table->timestamps();
                $table->unique(['page_path','selector']);
            });
        }
    }
    public function down(): void { Schema::dropIfExists('page_edits'); }
};
