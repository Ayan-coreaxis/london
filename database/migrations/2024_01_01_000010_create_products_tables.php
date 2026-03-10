<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(0.00);
            $table->string('sku')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->enum('status', ['active','draft'])->default('active');
            $table->timestamps();
        });
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('option_name');
            $table->enum('display_type', ['dropdown','buttons'])->default('dropdown');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->constrained('product_options')->onDelete('cascade');
            $table->string('value_label');
            $table->decimal('extra_price', 10, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('product_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('badge_color')->default('#d93025');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('link_slug')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('product_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('product_faqs');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_presets');
        Schema::dropIfExists('option_values');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('products');
    }
};