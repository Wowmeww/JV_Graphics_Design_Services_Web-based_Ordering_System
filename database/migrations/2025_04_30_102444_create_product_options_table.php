<?php

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->timestamps();
        });
        Schema::create('product_option_product_option_image', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductOption::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ProductOptionImage::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
