<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('order_artwork')) {
            Schema::create('order_artwork', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('order_item_id')->nullable();
                $table->string('file_name');           // original filename
                $table->string('file_path');           // storage path
                $table->string('file_type')->nullable(); // pdf, jpg, png, ai, etc
                $table->unsignedBigInteger('file_size')->default(0); // bytes
                $table->string('label')->nullable();    // "Front", "Back", "Page 1"
                $table->string('uploaded_by')->default('customer'); // "customer" or "admin"
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
                $table->foreign('order_item_id')->references('id')->on('order_items')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_artwork');
    }
};
