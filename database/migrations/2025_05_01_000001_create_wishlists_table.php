<?php

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\User;
use App\Models\Wishlist;
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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('wishlist_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wishlist::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ProductOption::class, 'option_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(12);
            $table->integer('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('wishlist_product');
    }
};
