<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Attributes: e.g. "Finished Size", "Printed Sides", "Stock", "Lamination"
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');                          // e.g. "Finished Size"
            $table->boolean('visible')->default(true);       // show on frontend
            $table->boolean('used_for_variations')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Attribute values: e.g. "85mm x 55mm (Square)", "85mm x 55mm (Round)"
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('product_attributes')->onDelete('cascade');
            $table->string('value');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Variations: each is a specific combination of attribute values
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Which attribute values make up each variation
        Schema::create('product_variation_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('product_variations')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('product_attributes')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('product_attribute_values')->onDelete('cascade');
            $table->timestamps();
        });

        // Quantities available for a product (e.g. 50, 100, 250, 500, 1000)
        Schema::create('product_quantities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Pricing per variation per quantity per turnaround
        // Also supports disabling specific qty or qty+turnaround combos
        Schema::create('product_variation_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('product_variations')->onDelete('cascade');
            $table->foreignId('turnaround_id')->constrained('product_turnarounds')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('disabled')->default(false);     // disable this specific cell
            $table->timestamps();

            $table->unique(['variation_id', 'turnaround_id', 'quantity'], 'var_turn_qty_unique');
        });

        // Disable entire quantity row for a variation
        Schema::create('product_variation_disabled_qty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('product_variations')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['variation_id', 'quantity'], 'var_qty_disabled_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variation_disabled_qty');
        Schema::dropIfExists('product_variation_pricing');
        Schema::dropIfExists('product_quantities');
        Schema::dropIfExists('product_variation_attributes');
        Schema::dropIfExists('product_variations');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('product_attributes');
    }
};
