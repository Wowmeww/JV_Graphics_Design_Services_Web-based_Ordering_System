<?php

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wishlist::class)->constrained()->cascadeOnDelete();
            $table->string('type')->default('base');
            $table->integer('quantity')->default(12);
            $table->integer('total_amount');
            $table->timestamps();
        });
        Schema::create('wishlist_item_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WishlistItem::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlist_item_product');
    }
};
