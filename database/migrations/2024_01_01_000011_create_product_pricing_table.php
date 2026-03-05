<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_turnarounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('label');          // e.g. "Express", "1 Working Day", "3-4 Working Days"
            $table->integer('working_days_min'); // e.g. 1
            $table->integer('working_days_max'); // e.g. 1 (same) or 4
            $table->string('artwork_deadline')->nullable(); // e.g. "5:00pm"
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turnaround_id')->constrained('product_turnarounds')->onDelete('cascade');
            $table->integer('quantity');       // e.g. 100
            $table->decimal('price', 10, 2);   // e.g. 12.99
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_pricing');
        Schema::dropIfExists('product_turnarounds');
    }
};